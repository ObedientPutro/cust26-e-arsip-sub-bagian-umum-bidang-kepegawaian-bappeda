<?php

namespace Database\Seeders;

use App\Enums\LetterTypeEnum;
use App\Models\Letter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IncomingLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('letters');

        $sourcePath = database_path('seeders/files/dummy_surat.pdf');

        $incomingLetters = [
            [
                'user_id' => 3, // ID Pegawai Citra Lestari
                'category_id' => 1, // Surat Undangan
                'letter_number' => '005/UN4/KM/2025',
                'subject' => 'Undangan Rapat Koordinasi Program Kerja 2026',
                'letter_date' => '2025-08-01',
                'sender' => 'Universitas Sam Ratulangi',
            ],
            [
                'user_id' => 4, // ID Pegawai Dian Permana
                'category_id' => 2, // Perjalanan Dinas
                'letter_number' => 'KP.02.01/123/KEMENDAGRI/VIII/2025',
                'subject' => 'Pemberitahuan Perjalanan Dinas Staf Ahli',
                'letter_date' => '2025-08-04',
                'sender' => 'Kementerian Dalam Negeri',
            ],
            [
                'user_id' => 5, // ID Pegawai Eko Prasetyo
                'category_id' => 9, // Anggaran
                'letter_number' => '910/BKA-SULUT/SP/VIII/2025',
                'subject' => 'Laporan Realisasi Anggaran Triwulan II',
                'letter_date' => '2025-08-05',
                'sender' => 'Badan Keuangan dan Aset Daerah Prov. Sulut',
            ],
            [
                'user_id' => 6, // ID Pegawai Fitriani
                'category_id' => 8, // Pendidikan & Pelatihan (Diklat)
                'letter_number' => 'BPSDM.01/45/2025',
                'subject' => 'Penawaran Program Diklat Peningkatan Kompetensi',
                'letter_date' => '2025-08-06',
                'sender' => 'BPSDM Provinsi Sulawesi Utara',
            ],
            [
                'user_id' => 7, // ID Pegawai Gilang Ramadhan
                'category_id' => 4, // Pengadaan Pegawai
                'subject' => 'Permohonan Data Formasi Pegawai Tahun 2025',
                'letter_number' => '810/BKD-PROV/REQ/25',
                'letter_date' => '2025-08-07',
                'sender' => 'Badan Kepegawaian Daerah Prov. Sulut',
            ],
            [
                'user_id' => 3,
                'category_id' => 1, // Surat Undangan
                'letter_number' => 'XII/IKAT/SULUT/2025',
                'subject' => 'Undangan Seminar Pembangunan Regional',
                'letter_date' => '2025-08-08',
                'sender' => 'Ikatan Ahli Perencanaan Indonesia (IAP)',
            ],
            [
                'user_id' => 4,
                'category_id' => 10, // Perlengkapan Kantor
                'letter_number' => 'INV/2025/08/00987',
                'subject' => 'Tagihan Pengadaan Alat Tulis Kantor Bulan Juli',
                'letter_date' => '2025-08-11',
                'sender' => 'PT ATK Jaya Sentosa',
            ],
            [
                'user_id' => 5,
                'category_id' => 3, // SPT
                'letter_number' => 'B-102/INSPEKTORAT/VIII/2025',
                'subject' => 'Pemberitahuan Audit Kinerja Internal',
                'letter_date' => '2025-08-11',
                'sender' => 'Inspektorat Daerah Provinsi Sulawesi Utara',
            ],
            [
                'user_id' => 6,
                'category_id' => 5, // Mutasi & Pengangkatan
                'letter_number' => 'SK.821/01/PNS/2025',
                'subject' => 'Salinan SK Kenaikan Pangkat a.n. Fitriani',
                'letter_date' => '2025-08-12',
                'sender' => 'Biro Kepegawaian Sekretariat Daerah',
            ],
            [
                'user_id' => 7,
                'category_id' => 1, // Surat Undangan
                'letter_number' => 'UND-045/DISKOMINFO/2025',
                'subject' => 'Sosialisasi Sistem Pemerintahan Berbasis Elektronik (SPBE)',
                'letter_date' => '2025-08-13',
                'sender' => 'Dinas Komunikasi dan Informatika Prov. Sulut',
            ],
        ];

        foreach ($incomingLetters as $incomingLetter) {
            $newFileName = Str::random(10) . '_dummy_surat.pdf';
            $destinationPath = 'letters/' . $newFileName;

            Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));

            Letter::create([
                'user_id' => $incomingLetter['user_id'],
                'category_id' => $incomingLetter['category_id'],
                'letter_number' => $incomingLetter['letter_number'],
                'subject' => $incomingLetter['subject'],
                'sender' => $incomingLetter['sender'],
                'letter_date' => $incomingLetter['letter_date'],
                'type' => LetterTypeEnum::Incoming,
                'file_path' => $destinationPath,
            ]);
        }
    }
}
