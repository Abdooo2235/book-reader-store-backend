<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes are organized into three main groups:
| 1. Public Routes - No authentication required
| 2. User Routes - Requires auth:sanctum middleware
| 3. Admin Routes - Requires auth:sanctum + admin middleware
|
*/

// ============================================================================
// CONTROLLERS
// ============================================================================
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\User\{
    ProfileController,
    BookController as UserBookController,
    MyBookController,
    CartController,
    OrderController,
    LibraryController,
    CollectionController,
    ProgressController,
    ReviewController,
    PreferenceController
};
use App\Http\Controllers\Admin\{
    DashboardController,
    BookController as AdminBookController,
    CategoryController as AdminCategoryController,
    UserController as AdminUserController,
    WalletController as AdminWalletController
};
use App\Http\Controllers\User\WalletController;
use App\Http\Middleware\AdminMiddleware;

// ============================================================================
// PUBLIC ROUTES (No Authentication)
// ============================================================================
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
});

Route::apiResource('books', BookController::class)->only(['index', 'show']);
Route::get('books/{book}/stream', [BookController::class, 'stream'])->name('books.stream');
Route::apiResource('categories', CategoryController::class)->only(['index']);
Route::get('books/{book}/reviews', [ReviewController::class, 'index'])->name('books.reviews.index');

// ============================================================================
// AUTHENTICATED USER ROUTES
// ============================================================================
Route::middleware('auth:sanctum')->group(function () {

    // ------ Auth Actions ------
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('user', [AuthController::class, 'user'])->name('auth.user');
    });

    // ------ Profile ------
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar.upload');
        Route::delete('avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
    });

    // ------ Book Submission ------
    Route::post('books', [UserBookController::class, 'store'])->name('books.store');
    Route::prefix('my-books')->name('my-books.')->group(function () {
        Route::get('/', [MyBookController::class, 'index'])->name('index');
        Route::delete('{book}', [MyBookController::class, 'destroy'])->name('destroy');
    });

    // ------ Cart ------
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('books/{book}', [CartController::class, 'add'])->name('add');
        Route::delete('books/{book}', [CartController::class, 'remove'])->name('remove');
        Route::post('checkout', [CartController::class, 'checkout'])->name('checkout');
    });

    // ------ Orders ------
    Route::apiResource('orders', OrderController::class)->only(['index', 'show']);

    // ------ Library & Favorites ------
    Route::prefix('library')->name('library.')->group(function () {
        Route::get('/', [LibraryController::class, 'index'])->name('index');
        Route::get('{book}/download', [LibraryController::class, 'download'])->name('download');
        Route::post('{book}/favorite', [LibraryController::class, 'favorite'])->name('favorite');
        Route::delete('{book}/favorite', [LibraryController::class, 'unfavorite'])->name('unfavorite');
    });
    Route::get('favorites', [LibraryController::class, 'favorites'])->name('favorites.index');

    // ------ Collections (Shelves) ------
    Route::prefix('collections')->name('collections.')->group(function () {
        Route::get('/', [CollectionController::class, 'index'])->name('index');
        Route::post('/', [CollectionController::class, 'store'])->name('store');
        Route::delete('{collection}', [CollectionController::class, 'destroy'])->name('destroy');
        Route::get('{collection}/books', [CollectionController::class, 'books'])->name('books');
        Route::post('{collection}/books', [CollectionController::class, 'addBook'])->name('books.add');
        Route::delete('{collection}/books/{book}', [CollectionController::class, 'removeBook'])->name('books.remove');
    });

    // ------ Reading Progress ------
    Route::prefix('progress')->name('progress.')->group(function () {
        Route::get('/', [ProgressController::class, 'index'])->name('index');
        Route::get('{book}', [ProgressController::class, 'show'])->name('show');
        Route::put('{book}', [ProgressController::class, 'update'])->name('update');
    });

    // ------ Reviews ------
    Route::post('books/{book}/reviews', [ReviewController::class, 'store'])->name('books.reviews.store');
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::put('{review}', [ReviewController::class, 'update'])->name('update');
        Route::delete('{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // ------ Preferences ------
    Route::prefix('preferences')->name('preferences.')->group(function () {
        Route::get('/', [PreferenceController::class, 'show'])->name('show');
        Route::put('/', [PreferenceController::class, 'update'])->name('update');
    });

    // ------ Wallet ------
    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');
});

// ============================================================================
// ADMIN ROUTES (Requires Admin Role)
// ============================================================================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth:sanctum', AdminMiddleware::class])
    ->group(function () {

        // ------ Dashboard ------
        Route::get('stats', [DashboardController::class, 'stats'])->name('stats');

        // ------ Books Management ------
        Route::prefix('books')->name('books.')->group(function () {
            Route::get('/', [AdminBookController::class, 'index'])->name('index');
            Route::get('{book}', [AdminBookController::class, 'show'])->name('show');
            Route::put('{book}', [AdminBookController::class, 'update'])->name('update');
            Route::delete('{book}', [AdminBookController::class, 'destroy'])->name('destroy');
            Route::put('{book}/approve', [AdminBookController::class, 'approve'])->name('approve');
            Route::put('{book}/reject', [AdminBookController::class, 'reject'])->name('reject');
        });

        // ------ Categories (Full CRUD) ------
        Route::apiResource('categories', AdminCategoryController::class);

        // ------ Users ------
        Route::apiResource('users', AdminUserController::class)->only(['index', 'show']);

        // ------ Wallet Management ------
        Route::get('users/{user}/wallet', [AdminWalletController::class, 'show'])->name('users.wallet');
        Route::post('users/{user}/wallet/topup', [AdminWalletController::class, 'topup'])->name('users.wallet.topup');
    });
