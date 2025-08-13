<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Dashboard');
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
            'Content-Disposition' => 'inline; filename="' . $letter->letter_number . '"',
        ];

        return response()->file($fullPath, $headers);
    }
}
