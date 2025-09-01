<?php

namespace App\Http\Controllers;

use App\Enums\DispositionStatusEnum;
use App\Enums\LetterTypeEnum;
use App\Models\Category;
use App\Models\Disposition;
use App\Models\Letter;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $dashboardData = $this->dashboardService->getDataForDashboard(auth()->user(), $filters);

        return Inertia::render('Dashboard/Dashboard', [
            'dashboardData' => $dashboardData,
            'categories' => Category::whereHas('letters', function ($q) {
                $q->where('type', LetterTypeEnum::Outgoing);
            })->get(['id', 'name']),
            'filters' => $filters,
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
