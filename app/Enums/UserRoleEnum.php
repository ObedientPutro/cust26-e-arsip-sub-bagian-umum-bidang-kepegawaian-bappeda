<?php

namespace App\Enums;

enum UserRoleEnum : String
{
    case Admin = 'admin';
    case Lead = 'pimpinan';
    case Employee = 'pegawai';

    /**
     * Mengembalikan label yang ramah untuk dibaca pengguna.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'admin',
            self::Lead => 'pimpinan',
            self::Employee => 'pegawai',
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
