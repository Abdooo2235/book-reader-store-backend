<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
  public function show(Request $request): JsonResponse
  {
    $user = $request->user();
    $user->load('preferences');

    return response()->json([
      'success' => true,
      'data' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'avatar_url' => $user->avatar_url,
        'email_verified_at' => $user->email_verified_at,
        'role' => $user->role,
        'created_at' => $user->created_at,
        'preferences' => $user->preferences,
        'reading_stats' => $user->reading_stats,
      ],
    ]);
  }

  public function update(Request $request): JsonResponse
  {
    $validated = $request->validate(['name' => 'sometimes|string|max:255']);
    $request->user()->update($validated);
    return response()->json(['success' => true, 'message' => 'Profile updated.', 'data' => $request->user()]);
  }

  public function uploadAvatar(Request $request): JsonResponse
  {
    $request->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);
    $user = $request->user();
    if ($user->avatar_path) Storage::disk('public')->delete($user->avatar_path);
    $path = $request->file('avatar')->store('avatars', 'public');
    $user->update(['avatar_path' => $path]);
    return response()->json(['success' => true, 'message' => 'Avatar uploaded.', 'data' => ['avatar_url' => $user->avatar_url]]);
  }

  public function deleteAvatar(Request $request): JsonResponse
  {
    $user = $request->user();
    if ($user->avatar_path) {
      Storage::disk('public')->delete($user->avatar_path);
      $user->update(['avatar_path' => null]);
    }
    return response()->json(['success' => true, 'message' => 'Avatar deleted.']);
  }
}
