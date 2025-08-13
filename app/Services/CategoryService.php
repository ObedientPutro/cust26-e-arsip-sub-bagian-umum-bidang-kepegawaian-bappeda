<?php

namespace App\Services;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;

class CategoryService
{
    public function store(StoreCategoryRequest $request): void
    {
        Category::create([
            'name' => $request->name,
            'classification_code' => $request->classification_code,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): void
    {
        $category->update([
            'name' => $request->name,
            'classification_code' => $request->classification_code,
        ]);
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
