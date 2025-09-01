<?php

namespace App\Services;

use App\Enums\DispositionStatusEnum;
use App\Models\Disposition;
use App\Models\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class DispositionService
{
    public function storeDisposition(array $validatedData, Letter $letter): void
    {
        DB::transaction(function () use ($validatedData, $letter) {
            $disposition = Disposition::create([
                'letter_id' => $letter->id,
                'instruction' => $validatedData['instruction'],
                'from_user_id' => auth()->id(),
            ]);

            $letter->update([
                'is_disposed' => true,
            ]);

            $recipientsWithStatus = [];
            foreach ($validatedData['recipients'] as $userId) {
                $recipientsWithStatus[$userId] = ['status' => DispositionStatusEnum::Sent];
            }

            $disposition->recipients()->attach($recipientsWithStatus);
        });
    }

    public function updateDisposition(array $validatedData, Disposition $disposition): void
    {
        DB::transaction(function () use ($validatedData, $disposition) {
            $disposition->update([
                'instruction' => $validatedData['instruction'],
            ]);

            $recipientsWithStatus = [];
            foreach ($validatedData['recipients'] as $userId) {
                $recipientsWithStatus[$userId] = ['status' => DispositionStatusEnum::Sent->value];
            }

            $disposition->recipients()->sync($recipientsWithStatus);
        });
    }

    public function deleteDisposition(Disposition $disposition): void
    {
        $disposition->delete();
    }

    /**
     * Membuat lembar disposisi, menggabungkannya dengan surat asli,
     * dan mengembalikan konten PDF yang sudah digabung.
     *
     * @param Letter $letter
     * @return string
     * @throws \Exception
     */
    public function generateAndMergeDispositionPdf(Letter $letter): string
    {
        $letter->load(['user', 'dispositions.user', 'dispositions.recipients']);

        // --- MEMBUAT PDF LEMBAR DISPOSISI ---
        $dispositionPdf = Pdf::loadView('pdf.lembar_disposisi', ['letter' => $letter]);
        $tempDispositionPath = 'temp/disposisi_' . $letter->id . '_' . time() . '.pdf';
        Storage::put($tempDispositionPath, $dispositionPdf->output());

        // --- MENGGABUNGKAN PDF ---
        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF(Storage::path($tempDispositionPath), 'all');

        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            $pdfMerger->addPDF(Storage::disk('public')->path($letter->file_path), 'all');
        }

        $pdfMerger->merge();
        $mergedPdfContent = $pdfMerger->output();

        Storage::delete($tempDispositionPath);

        return $mergedPdfContent;
    }
}
