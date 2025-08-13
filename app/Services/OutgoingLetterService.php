<?php

namespace App\Services;

use App\Enums\LetterTypeEnum;
use App\Models\Category;
use App\Models\Letter;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OutgoingLetterService
{
    /**
     * Menyimpan data surat keluar (bisa otomatis atau manual).
     */
    public function saveData(array $data): bool
    {
        // Jika BUKAN mode arsip, generate nomor otomatis
        if (empty($data['is_archive_mode'])) {
            DB::beginTransaction();
            try {
                $parts = $this->generateNumberParts();
                $data['letter_number'] = $this->buildLetterNumberString($parts, $data['category_id']);
                $data['letter_date'] = now()->toDateString();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        if (isset($data['attachment_file'])) {
            $data['file_path'] = $data['attachment_file']->store('letters', 'public');
        }

        $data['type'] = LetterTypeEnum::Outgoing;
        $data['user_id'] = auth()->id();

        try {
            Letter::create($data);
            if (empty($data['is_archive_mode'])) {
                DB::commit();
            }
            return true;
        } catch (QueryException $e) {
            if (empty($data['is_archive_mode'])) {
                DB::rollBack();
            }
            // Cek jika error disebabkan oleh unique constraint violation
            if ($e->errorInfo[1] == 1062) { // Kode error umum untuk duplikat entry
                return false; // Gagal karena race condition
            }
            throw $e; // Lemparkan error lain jika bukan duplikasi
        }
    }

    /**
     * Mengambil komponen-komponen untuk nomor surat baru.
     */
    public function generateNumberParts(): array
    {
        $currentYear = date('Y');

        $lastLetterNumber = Letter::where('type', LetterTypeEnum::Outgoing)
            ->whereYear('letter_date', $currentYear)
            ->pluck('letter_number');

        $maxSequence = $lastLetterNumber
            ->map(function ($number) {
                return (int) explode('/', $number)[0];
            })
            ->max();

        $nextSequence = ($maxSequence ?? 0) + 1;

        return [
            'sequence' => $nextSequence,
            'roman_month' => $this->getRomanMonth(date('n')),
            'year' => $currentYear,
            'unit_code' => env('KODE_UNIT_KERJA', 'BAPPEDA.SULUT'),
        ];
    }

    /**
     * Membangun string nomor surat dari komponen-komponennya.
     */
    public function buildLetterNumberString(array $parts, int $categoryId): string
    {
        $category = Category::find($categoryId);
        $classificationCode = $category ? $category->classification_code : 'XXX';
        $sequencePadded = str_pad($parts['sequence'], 3, '0', STR_PAD_LEFT);

        return "{$sequencePadded}/{$classificationCode}/{$parts['unit_code']}/{$parts['roman_month']}/{$parts['year']}";
    }

    /**
     * Helper untuk mengubah angka bulan menjadi romawi.
     */
    private function getRomanMonth(int $month): string
    {
        $map = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $map[$month];
    }

    /**
     * Memperbarui data surat yang ada.
     */
    public function updateData(array $data, Letter $letter): void
    {
        // Handle file upload jika ada file baru
        if (isset($data['attachment_file'])) {
            // Hapus file lama jika ada
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }

            $data['file_path'] = $data['attachment_file']->store('letters', 'public');
        }

        $letter->update($data);
    }

    /**
     * Menghapus data surat beserta file lampirannya.
     */
    public function deleteData(Letter $letter): void
    {
        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();
    }
}
