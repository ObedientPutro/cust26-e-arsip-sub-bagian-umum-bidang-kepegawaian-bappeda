<?php

namespace Database\Seeders;

use App\Enums\LetterTypeEnum;
use App\Models\Letter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OutgoingLetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('letters');

        $sourcePath = database_path('seeders/files/dummy_surat.pdf');
        $fileContent = file_get_contents($sourcePath);

        $outgoingLetters = [
            [
                'user_id' => 3, 'category_id' => 3,
                'letter_number' => '001/094/UM.BAPPEDA/VIII/2025',
                'subject' => 'Surat Perintah Tugas untuk Monitoring Proyek Strategis',
                'recipient' => 'Kepala Dinas Pekerjaan Umum Prov. Sulut',
                'letter_date' => '2025-08-01',
            ],
            [
                'user_id' => 4, 'category_id' => 1,
                'letter_number' => '002/005/UM.BAPPEDA/VIII/2025',
                'subject' => 'Undangan Rapat Forum Perangkat Daerah',
                'recipient' => 'Seluruh Kepala SKPD di Lingkungan Pemprov Sulut',
                'letter_date' => '2025-08-02',
            ],
            [
                'user_id' => 5, 'category_id' => 2,
                'letter_number' => '003/090/UM.BAPPEDA/VIII/2025',
                'subject' => 'Surat Perjalanan Dinas ke Kementerian PPN/Bappenas',
                'recipient' => 'Direktorat Jenderal Pembangunan Daerah',
                'letter_date' => '2025-08-04',
            ],
            [
                'user_id' => 6, 'category_id' => 8,
                'letter_number' => '004/890/UM.BAPPEDA/VIII/2025',
                'subject' => 'Permohonan Narasumber untuk Pelatihan Perencanaan',
                'recipient' => 'Rektor Universitas Sam Ratulangi',
                'letter_date' => '2025-08-05',
            ],
            [
                'user_id' => 7, 'category_id' => 9,
                'letter_number' => '005/910/UM.BAPPEDA/VIII/2025',
                'subject' => 'Pengajuan Usulan Anggaran Tambahan Tahun 2025',
                'recipient' => 'Kepala Badan Keuangan dan Aset Daerah Prov. Sulut',
                'letter_date' => '2025-08-06',
            ],
            [
                'user_id' => 3, 'category_id' => 10,
                'letter_number' => '006/070/UM.BAPPEDA/VIII/2025',
                'subject' => 'Permintaan Penawaran Harga Pengadaan Komputer',
                'recipient' => 'Direktur CV. Mitra Komputindo',
                'letter_date' => '2025-08-07',
            ],
            [
                'user_id' => 4, 'category_id' => 1,
                'letter_number' => '007/005/UM.BAPPEDA/VIII/2025',
                'subject' => 'Konfirmasi Kehadiran dalam Acara Musrenbang',
                'recipient' => 'Panitia Musrenbang Provinsi Sulawesi Utara',
                'letter_date' => '2025-08-08',
            ],
            [
                'user_id' => 5, 'category_id' => 5,
                'letter_number' => '008/820/UM.BAPPEDA/VIII/2025',
                'subject' => 'Usulan Promosi Jabatan Staf Perencanaan',
                'recipient' => 'Kepala Badan Kepegawaian Daerah Prov. Sulut',
                'letter_date' => '2025-08-11',
            ],
            [
                'user_id' => 6, 'category_id' => 3,
                'letter_number' => '009/094/UM.BAPPEDA/VIII/2025',
                'subject' => 'Surat Tugas Pendampingan Tim Verifikasi Lapangan',
                'recipient' => 'Inspektorat Daerah Provinsi Sulawesi Utara',
                'letter_date' => '2025-08-12',
            ],
            [
                'user_id' => 7, 'category_id' => 7,
                'letter_number' => '010/851/UM.BAPPEDA/VIII/2025',
                'subject' => 'Persetujuan Cuti Tahunan Pegawai',
                'recipient' => 'Pegawai ybs.',
                'letter_date' => '2025-08-13',
            ],
        ];

        foreach ($outgoingLetters as $letterData) {
            $newFileName = Str::random(10) . '_dummy_surat.pdf';
            $destinationPath = 'letters/' . $newFileName;
            Storage::disk('public')->put($destinationPath, $fileContent);

            Letter::create([
                'user_id' => $letterData['user_id'],
                'category_id' => $letterData['category_id'],
                'letter_number' => $letterData['letter_number'],
                'subject' => $letterData['subject'],
                'recipient' => $letterData['recipient'],
                'letter_date' => $letterData['letter_date'],
                'type' => LetterTypeEnum::Outgoing,
                'file_path' => $destinationPath,
            ]);
        }
    }
}
