<?php

namespace App\Services;

use App\Enums\DispositionStatusEnum;
use App\Enums\LetterTypeEnum;
use App\Enums\UserRoleEnum;
use App\Models\Category;
use App\Models\Letter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Mengambil data yang sesuai untuk dashboard berdasarkan peran pengguna.
     */
    public function getDataForDashboard(User $user): array
    {
        return match ($user->role) {
            UserRoleEnum::Admin => $this->getAdminData(),
            UserRoleEnum::Lead => $this->getPimpinanData(),
            UserRoleEnum::Employee => $this->getPegawaiData($user),
            default => [],
        };
    }

    /**
     * Data untuk dashboard Admin.
     */
    private function getAdminData(): array
    {
        return [
            'stats' => [
                'users' => User::count(),
                'categories' => Category::count(),
                'incoming_letters' => Letter::where('type', LetterTypeEnum::Incoming)->count(),
                'outgoing_letters' => Letter::where('type', LetterTypeEnum::Outgoing)->count(),
            ],
            'chart' => $this->getLetterTrendData(),
        ];
    }

    /**
     * Data untuk dashboard Pimpinan.
     */
    private function getPimpinanData(): array
    {
        return [
            'chart' => $this->getLetterTrendData(),
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
    private function getLetterTrendData(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;

        $incoming = Letter::where('type', LetterTypeEnum::Incoming)
            ->whereBetween('letter_date', [$startOfMonth, $endOfMonth])
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(letter_date) as date'),
                DB::raw('COUNT(*) as count')
            ])->pluck('count', 'date');

        $outgoing = Letter::where('type', LetterTypeEnum::Outgoing)
            ->whereBetween('letter_date', [$startOfMonth, $endOfMonth])
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(letter_date) as date'),
                DB::raw('COUNT(*) as count')
            ])->pluck('count', 'date');

        $labels = [];
        $incomingData = [];
        $outgoingData = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $startOfMonth->copy()->addDays($i - 1)->format('Y-m-d');
            $dayLabel = $startOfMonth->copy()->addDays($i - 1)->format('d M');

            $labels[] = $dayLabel;
            $incomingData[] = $incoming[$date] ?? 0;
            $outgoingData[] = $outgoing[$date] ?? 0;
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
