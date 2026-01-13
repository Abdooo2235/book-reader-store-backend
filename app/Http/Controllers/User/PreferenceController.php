<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
  public function show(Request $request): JsonResponse
  {
    return response()->json(['success' => true, 'data' => $request->user()->preferences]);
  }

  public function update(Request $request): JsonResponse
  {
    $validated = $request->validate(['theme' => 'sometimes|in:light,dark', 'font_size' => 'sometimes|integer|min:12|max:32']);
    $request->user()->preferences->update($validated);
    return response()->json(['success' => true, 'message' => 'Preferences updated.', 'data' => $request->user()->preferences]);
  }
}
