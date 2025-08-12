<?php

namespace App\Enums;

enum LetterTypeEnum : String
{
    case Incoming = 'masuk';
    case Outgoing = 'keluar';

    /**
     * Mengembalikan label yang ramah untuk dibaca pengguna.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Incoming => 'masuk',
            self::Outgoing => 'keluar',
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
