<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
  /**
   * Get wallet balance (transaction history is admin-only)
   */
  public function index(Request $request): JsonResponse
  {
    $user = $request->user();

    return response()->json([
      'success' => true,
      'data' => [
        'balance' => round($user->balance, 2),
      ]
    ]);
  }
}
