<?php

namespace App\Services;

use App\Enums\DispositionStatusEnum;
use App\Models\Disposition;
use App\Models\Letter;
use Illuminate\Support\Facades\DB;

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
}
