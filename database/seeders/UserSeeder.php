<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'administrator@gmail.com',
                'username' => 'admin',
                'role' => UserRoleEnum::Admin,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budisantoso@gmail.com',
                'username' => 'pimpinan',
                'role' => UserRoleEnum::Lead,
            ],
            [
                'name' => 'Citra Lestari',
                'email' => 'citralestari@gmail.com',
                'username' => 'citra.lestari',
                'role' => 'pegawai',
            ],
            [
                'name' => 'Dian Permana',
                'email' => 'dianpermana@gmail.com',
                'username' => 'dian.permana',
                'role' => 'pegawai',
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'ekoprasetyo@gmail.com',
                'username' => 'eko.prasetyo',
                'role' => 'pegawai',
            ],
            [
                'name' => 'Fitriani',
                'email' => 'fitriani@gmail.com',
                'username' => 'fitriani',
                'role' => 'pegawai',
            ],
            [
                'name' => 'Gilang Ramadhan',
                'email' => 'gilangramadhan@gmail.com',
                'username' => 'gilang.ramadhan',
                'role' => 'pegawai',
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'username' => $user['username'],
                'role' => $user['role'],
                'password' => Hash::make('password'),
            ]);
        }
    }
}
