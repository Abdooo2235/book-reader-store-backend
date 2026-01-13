<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::where('role', 'user')
            ->withCount(['orders', 'reviews', 'submittedBooks'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json(['success' => true, 'data' => $users]);
    }

    public function show(User $user): JsonResponse
    {
        $user->loadCount(['orders', 'reviews', 'submittedBooks', 'collections']);
        return response()->json(['success' => true, 'data' => $user]);
    }
}
