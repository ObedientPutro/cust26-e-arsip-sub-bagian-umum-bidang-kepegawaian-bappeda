<?php

namespace App\Http\Controllers;

use App\Enums\DispositionStatusEnum;
use App\Models\Disposition;
use App\Models\Letter;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index()
    {
        $dashboardData = $this->dashboardService->getDataForDashboard(auth()->user());

        return Inertia::render('Dashboard/Dashboard', [
            'dashboardData' => $dashboardData
        ]);
    }

    public function markDispositionAsRead(Disposition $disposition)
    {
        auth()->user()->receivedDispositions()->updateExistingPivot($disposition->id, [
            'status' => DispositionStatusEnum::Read,
        ]);

        return back()->with('success', 'Disposisi telah ditandai sebagai dibaca.');
    }

    public function streamFile(Letter $letter)
    {
        $path = $letter->file_path;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = Storage::disk('public')->path($path);

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $letter->letter_number . '.pdf' . '"',
        ];

        return response()->file($fullPath, $headers);
    }
}
