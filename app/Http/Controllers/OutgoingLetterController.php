<?php

namespace App\Http\Controllers;

use App\Enums\LetterTypeEnum;
use App\Http\Requests\OutgoingLetter\StoreOutgoingLetterRequest;
use App\Http\Requests\OutgoingLetter\UpdateOutgoingLetterRequest;
use App\Models\Category;
use App\Models\Letter;
use App\Services\OutgoingLetterService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OutgoingLetterController extends Controller
{
    public function __construct(
        protected OutgoingLetterService $outgoingLetterService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $query = Letter::query()
            ->with(['category', 'user'])
            ->where('type', LetterTypeEnum::Outgoing)
            ->latest();

        $query->when($request->input('search'), function ($q, $search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('letter_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('sender', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        });

        $letters = $query->paginate(10)->withQueryString();

        return Inertia::render('OutgoingLetter/OutgoingLetterList', [
            'letters' => $letters,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $categories = Category::all(['id', 'name', 'classification_code'])->keyBy('id');
        $numberParts = $this->outgoingLetterService->generateNumberParts();

        return Inertia::render('OutgoingLetter/OutgoingLetterForm', [
            'categories' => $categories,
            'numberParts' => $numberParts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOutgoingLetterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $success = $this->outgoingLetterService->saveData($validated);

        if (!$success) {
            // Jika terjadi race condition (duplikasi nomor)
            return back()
                ->withInput()
                ->with('error', 'Terjadi duplikasi nomor surat karena ada pengguna lain yang menyimpan pada saat bersamaan. Silakan periksa kembali dan simpan lagi.');
        }

        return redirect()->route('outgoingLetter.index')->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Letter $letter): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('outgoingLetter.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Letter $letter): \Inertia\Response
    {
        return Inertia::render('OutgoingLetter/OutgoingLetterForm', [
            'letter' => $letter,
            'categories' => Category::all(['id', 'name'])->keyBy('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutgoingLetterRequest $request, Letter $letter): \Illuminate\Http\RedirectResponse
    {
        $this->outgoingLetterService->updateData($request->validated(), $letter);

        return redirect()->route('outgoingLetter.index')->with('success', 'Surat keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter): \Illuminate\Http\RedirectResponse
    {
        $this->outgoingLetterService->deleteData($letter);

        return redirect()->route('outgoingLetter.index')->with('success', 'Surat keluar berhasil dihapus.');
    }
}
