<?php

namespace App\Http\Controllers;

use App\Enums\LetterTypeEnum;
use App\Http\Requests\IncomingLetter\StoreIncomingLetterRequest;
use App\Http\Requests\IncomingLetter\UpdateIncomingLetterRequest;
use App\Models\Category;
use App\Models\Letter;
use App\Services\IncomingLetterService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncomingLetterController extends Controller
{
    public function __construct(
        protected IncomingLetterService $incomingLetterService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $query = Letter::query()
            ->with(['category', 'user'])
            ->where('type', LetterTypeEnum::Incoming)
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

        return Inertia::render('IncomingLetter/IncomingLetterList', [
            'letters' => $letters,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('IncomingLetter/IncomingLetterForm', [
            'categories' => Category::all(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomingLetterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->incomingLetterService->saveData($request->validated());

        return redirect()->route('incomingLetter.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Letter $letter)
    {
        dd($letter);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Letter $letter): \Inertia\Response
    {
        return Inertia::render('IncomingLetter/IncomingLetterForm', [
            'letter' => $letter,
            'categories' => Category::all(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomingLetterRequest $request, Letter $letter): \Illuminate\Http\RedirectResponse
    {
        $this->incomingLetterService->updateData($request->validated(), $letter);

        return redirect()->route('incomingLetter.index')->with('success', 'Surat masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter): \Illuminate\Http\RedirectResponse
    {
        $this->incomingLetterService->deleteData($letter);

        return redirect()->route('incomingLetter.index')->with('success', 'Surat masuk berhasil dihapus.');
    }
}
