<?php

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentMethodeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Author\AuthController as AuthorAuthController;
use App\Http\Controllers\Author\BookController;
use App\Http\Controllers\Author\CategoryController as AuthorCategoryController;
use App\Http\Controllers\Author\OrderController as AuthorOrderController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\BookController as CustomerBookController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CategoryController as CustomerCategoryController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentMethodeController as CustomerPaymentMethodeController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthorMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Get authenticated user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('customer/sign-up', [CustomerAuthController::class, 'signup']);
Route::post('author/sign-up', [AuthorAuthController::class, 'signup']);

// Admin Routes
Route::prefix('admin')->middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('author', AuthorController::class);
    Route::put('author/{author}/approve', [AuthorController::class, 'approve']);
    Route::apiResource('payment-method', PaymentMethodeController::class);
    Route::get('order', [AdminOrderController::class, 'index']);
    Route::put('order/{order_id}/status', [AdminOrderController::class, 'updateStatus']);
    Route::get('user', [UserController::class, 'index']);
    Route::put('user/{user_id}/block', [UserController::class, 'block']);
    Route::put('user/{user_id}/unblock', [UserController::class, 'unblock']);
});

// Author Routes
Route::prefix('author')->middleware(['auth:sanctum', AuthorMiddleware::class])->group(function () {
    Route::apiResource('book', BookController::class)->only(['index', 'store', 'update']);
    Route::get('book/all', [BookController::class, 'showAll']);
    Route::put('book/{book_id}/stock', [BookController::class, 'updateStock']);
    Route::get('book/requests', [BookController::class, 'getRequests']);
    Route::post('book/request', [BookController::class, 'addRequest']);
    Route::apiResource('category', AuthorCategoryController::class)->only('index');
    Route::get('order', [AuthorOrderController::class, 'index']);
});

// Customer Routes
Route::prefix('customer')->middleware(['auth:sanctum', CustomerMiddleware::class])->group(function () {
    Route::apiResource('book', CustomerBookController::class)->only(['index', 'show']);
    Route::apiResource('category', CustomerCategoryController::class)->only('index');
    Route::apiResource('cart', CartController::class)->except('store');
    Route::post('cart/{book}', [CartController::class, 'store']);
    Route::get('order', [CustomerOrderController::class, 'index']);
    Route::get('payment-method', [CustomerPaymentMethodeController::class, 'index']);
    Route::put('profile', [CustomerAuthController::class, 'editProfile']);
});
