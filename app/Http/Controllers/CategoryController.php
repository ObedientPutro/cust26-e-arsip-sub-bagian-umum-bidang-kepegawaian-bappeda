<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $query = Category::query()
            ->latest();

        $query->when($request->input('search'), function ($q, $search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('classification_code', 'like', "%{$search}%");
            });
        });

        $categories = $query->paginate(10)->withQueryString();

        return Inertia::render('Category/CategoryList', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Category/CategoryForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->categoryService->store($request);

        return redirect()->route('category.index')->with('success', 'Kategori '. $request->name .' Berhasil di Tambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('category.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): \Inertia\Response
    {
        return Inertia::render('Category/CategoryForm', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): \Illuminate\Http\RedirectResponse
    {
        $this->categoryService->update($request, $category);

        return redirect()->route('category.index')->with('success', 'Kategori '. $request->name .' Berhasil di Ubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): \Illuminate\Http\RedirectResponse
    {
        $this->categoryService->delete($category);

        return redirect()->route('category.index')->with('success', 'Kategori '. $category->name .' Berhasil di Hapus');
    }
}
