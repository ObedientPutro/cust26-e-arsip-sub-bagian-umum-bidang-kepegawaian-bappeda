<?php

namespace App\Http\Controllers;

use App\Http\Requests\Disposition\StoreDispositionRequest;
use App\Http\Requests\Disposition\UpdateDispositionRequest;
use App\Models\Disposition;
use App\Models\Letter;
use App\Models\User;
use App\Services\DispositionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class DispositionController extends Controller
{
    public function __construct(
        protected DispositionService $dispositionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('incomingLetter.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Letter $letter): \Inertia\Response
    {
        if (Gate::denies('manage-disposition')) abort(403);

        $users = User::query()
            ->where('role', 'pegawai')
            ->when($request->input('search'), function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('Disposition/DispositionForm', [
            'letter' => $letter,
            'users' => $users,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDispositionRequest $request, Letter $letter): \Illuminate\Http\RedirectResponse
    {
        $this->dispositionService->storeDisposition($request->validated(), $letter);

        return redirect()->route('incomingLetter.index')->with('success', 'Disposisi berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Letter $letter): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('incomingLetter.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Disposition $disposition): \Inertia\Response
    {
        if (Gate::denies('manage-disposition')) abort(403);

        $disposition->load(['recipients']);
        $letter = Letter::findOrFail($disposition->letter_id);

        $users = User::query()
            ->where('role', 'pegawai')
            ->when($request->input('search'), fn($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->paginate(5)->withQueryString();

        return Inertia::render('Disposition/DispositionForm', [
            'disposition' => $disposition,
            'letter' => $letter,
            'users' => $users,
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDispositionRequest $request, Disposition $disposition): \Illuminate\Http\RedirectResponse
    {
        $this->dispositionService->updateDisposition($request->validated(), $disposition);

        return redirect()->route('incomingLetter.view', $disposition->letter_id)->with('success', 'Disposisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disposition $disposition): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('incomingLetter.index');
    }

    /**
     * Generate and merge the disposition sheet with the original letter PDF.
     */
    public function generateDispositionSheetPdf(Letter $letter)
    {
        try {
            $mergedPdfContent = $this->dispositionService->generateAndMergeDispositionPdf($letter);
            $fileName = 'Disposisi Surat Masuk - ' . str_replace('/', '_', $letter->letter_number) . '.pdf';

            return response($mergedPdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$fileName.'"'
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }
}
