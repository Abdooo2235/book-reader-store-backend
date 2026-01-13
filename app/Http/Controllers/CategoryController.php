<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
  public function index(): JsonResponse
  {
    $categories = QueryBuilder::for(Category::class)
      ->allowedSorts(['name', 'created_at'])
      ->defaultSort('name')
      ->withCount(['books' => fn($q) => $q->where('status', 'approved')])
      ->get();

    return response()->json(['success' => true, 'data' => $categories]);
  }
}
