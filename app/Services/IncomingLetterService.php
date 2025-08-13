<?php

namespace App\Services;

use App\Enums\LetterTypeEnum;
use App\Models\Letter;
use Illuminate\Support\Facades\Storage;

class IncomingLetterService
{
    public function saveData(array $data): void
    {
        if (isset($data['attachment_file'])) {
            $data['file_path'] = $data['attachment_file']->store('letters', 'public');
        }

        $data['type'] = LetterTypeEnum::Incoming;
        $data['user_id'] = auth()->id();

        Letter::create($data);
    }

    public function updateData(array $data, Letter $letter): void
    {
        // Handle file upload jika ada file baru
        if (isset($data['attachment_file'])) {
            // Hapus file lama jika ada
            if ($letter->file_path) {
                Storage::disk('public')->delete($letter->file_path);
            }

            $data['file_path'] = $data['attachment_file']->store('letters', 'public');
        }

        $letter->update($data);
    }

    public function deleteData(Letter $letter): void
    {
        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();
    }
}
