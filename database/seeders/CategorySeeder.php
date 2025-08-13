<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // --- UMUM ---
            [
                'name' => 'Surat Undangan',
                'classification_code' => '005',
            ],
            [
                'name' => 'Perjalanan Dinas',
                'classification_code' => '090',
            ],
            [
                'name' => 'Surat Perintah Tugas (SPT)',
                'classification_code' => '094',
            ],

            // --- KEPEGAWAIAN ---
            [
                'name' => 'Pengadaan Pegawai',
                'classification_code' => '810',
            ],
            [
                'name' => 'Mutasi & Pengangkatan Pegawai',
                'classification_code' => '820',
            ],
            [
                'name' => 'Penilaian Kinerja Pegawai',
                'classification_code' => '863',
            ],
            [
                'name' => 'Cuti Pegawai',
                'classification_code' => '851',
            ],
            [
                'name' => 'Pendidikan & Pelatihan (Diklat)',
                'classification_code' => '890',
            ],

            // --- KEUANGAN & PERLENGKAPAN ---
            [
                'name' => 'Anggaran',
                'classification_code' => '910',
            ],
            [
                'name' => 'Perlengkapan Kantor',
                'classification_code' => '070',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'classification_code' => $category['classification_code'],
            ]);
        }
    }
}
