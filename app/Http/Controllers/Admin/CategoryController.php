<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => Category::withCount('books')->orderBy('name')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string|max:100|unique:categories']);
        return response()->json(['success' => true, 'message' => 'Category created.', 'data' => Category::create($validated)], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $category->loadCount('books')]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate(['name' => 'required|string|max:100|unique:categories,name,' . $category->id]);
        $category->update($validated);
        return response()->json(['success' => true, 'message' => 'Category updated.', 'data' => $category]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->books()->count() > 0) return response()->json(['success' => false, 'message' => 'Has books.'], 400);
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted.']);
    }
}
