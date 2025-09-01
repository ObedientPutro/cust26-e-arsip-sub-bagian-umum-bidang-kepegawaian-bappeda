<?php

namespace App\Services;

use App\Enums\DispositionStatusEnum;
use App\Enums\LetterTypeEnum;
use App\Enums\UserRoleEnum;
use App\Models\Category;
use App\Models\Letter;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Mengambil data yang sesuai untuk dashboard berdasarkan peran pengguna.
     */
    public function getDataForDashboard(User $user, array $filters = []): array
    {
        return match ($user->role) {
            UserRoleEnum::Admin => $this->getAdminData($filters),
            UserRoleEnum::Lead => $this->getPimpinanData($filters),
            UserRoleEnum::Employee => $this->getPegawaiData($user),
            default => [],
        };
    }

    /**
     * Data untuk dashboard Admin.
     */
    private function getAdminData(array $filters): array
    {
        return [
            'stats' => [
                'users' => User::count(),
                'categories' => Category::count(),
                'incoming_letters' => Letter::where('type', LetterTypeEnum::Incoming)->count(),
                'outgoing_letters' => Letter::where('type', LetterTypeEnum::Outgoing)->count(),
            ],
            'chart' => $this->getLetterTrendData($filters),
        ];
    }

    /**
     * Data untuk dashboard Pimpinan.
     */
    private function getPimpinanData(array $filters): array
    {
        return [
            'chart' => $this->getLetterTrendData($filters),
        ];
    }

    /**
     * Data untuk dashboard Pegawai.
     */
    private function getPegawaiData(User $user): array
    {
        // Ambil disposisi yang berelasi dengan surat (eager loading)
        $dispositions = $user->receivedDispositions()->with('letter:id,subject,sender')->latest()->get();

        return [
            'unread_dispositions' => $dispositions->where('pivot.status', DispositionStatusEnum::Sent),
            'read_dispositions' => $dispositions->where('pivot.status', DispositionStatusEnum::Read),
        ];
    }

    /**
     * Menyiapkan data untuk grafik tren surat bulanan.
     */
    private function getLetterTrendData(array $filters = []): array
    {
        $startDate = isset($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
        $endDate = isset($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now()->endOfMonth();

        $incoming = Letter::where('type', LetterTypeEnum::Incoming)
            ->whereBetween('letter_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(letter_date) as date'),
                DB::raw('COUNT(*) as count')
            ])->pluck('count', 'date');

        $outgoingQuery = Letter::where('type', LetterTypeEnum::Outgoing)
            ->whereBetween('letter_date', [$startDate, $endDate]);

        if (!empty($filters['category_id'])) {
            $outgoingQuery->where('category_id', $filters['category_id']);
        }

        $outgoing = $outgoingQuery->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(letter_date) as date'),
                DB::raw('COUNT(*) as count')
            ])->pluck('count', 'date');

        $labels = [];
        $incomingData = [];
        $outgoingData = [];

        // Buat label dan data berdasarkan rentang tanggal yang dipilih
        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $incomingData[] = $incoming[$dateString] ?? 0;
            $outgoingData[] = $outgoing[$dateString] ?? 0;
        }

        return [
            'labels' => $labels,
            'series' => [
                ['name' => 'Surat Masuk', 'data' => $incomingData],
                ['name' => 'Surat Keluar', 'data' => $outgoingData],
            ]
        ];
    }
}
