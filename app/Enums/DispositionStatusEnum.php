<?php

namespace App\Enums;

enum DispositionStatusEnum : String
{
    case Sent = 'dikirim';
    case Read = 'dibaca';

    /**
     * Mengembalikan label yang ramah untuk dibaca pengguna.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Sent => 'dikirim',
            self::Read => 'dibaca',
        };
    }

    /**
     * Mengubah semua dalam format array untuk dropdown atau filter.
     * @return array
     */
    public static function toArray(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[] = [
                'id' => $case->value,
                'name' => $case->getLabel(),
            ];
        }
        return $cases;
    }
}
