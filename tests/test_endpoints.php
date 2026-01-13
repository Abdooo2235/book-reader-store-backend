<?php

// Comprehensive API Test Script
// Run with: php artisan tinker < tests/test_endpoints.php

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Collection;
use App\Models\Review;
use App\Models\ReadingProgress;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Hash;

echo "\n========================================\n";
echo "  BOOK READER API - ENDPOINT TESTS\n";
echo "========================================\n\n";

$passed = 0;
$failed = 0;

function test($name, $condition, $details = '')
{
  global $passed, $failed;
  if ($condition) {
    echo "✅ PASS: $name\n";
    $passed++;
  } else {
    echo "❌ FAIL: $name";
    if ($details) echo " - $details";
    echo "\n";
    $failed++;
  }
}

// ==================== DATABASE TESTS ====================
echo "\n--- DATABASE TESTS ---\n";

test('Categories exist', Category::count() === 15, 'Expected 15 categories');
test('Admin user exists', User::where('email', 'admin@bookreader.com')->exists());

// ==================== MODEL TESTS ====================
echo "\n--- MODEL TESTS ---\n";

// Test User creation with auto-create
$testUser = User::create([
  'name' => 'Test User',
  'email' => 'test' . time() . '@test.com',
  'password' => Hash::make('password'),
  'role' => 'user',
]);

test('User created', $testUser->exists);
test('User cart auto-created', $testUser->cart !== null);
test('User collections auto-created (3)', $testUser->collections()->count() === 3);
test('User preferences auto-created', $testUser->preferences !== null);
test(
  'Default collections have correct names',
  $testUser->collections()->pluck('name')->toArray() == ['Reading', 'Already Read', 'Planning']
);

// Test Book model
$category = Category::first();
$testBook = Book::create([
  'title' => 'Test Book',
  'author' => 'Test Author',
  'description' => 'Test description',
  'category_id' => $category->id,
  'file_type' => 'pdf',
  'status' => 'approved',
  'number_of_pages' => 100,
  'created_by' => $testUser->id,
]);

test('Book created', $testBook->exists);
test('Book belongs to category', $testBook->category->id === $category->id);
test('Book belongs to creator', $testBook->creator->id === $testUser->id);
test('Book status is approved', $testBook->isApproved());

// ==================== CART TESTS ====================
echo "\n--- CART TESTS ---\n";

$cart = $testUser->cart;
test('Cart exists', $cart !== null);

$cart->addBook($testBook->id);
test('Add book to cart', $cart->hasBook($testBook->id));
test('Cart total items is 1', $cart->total_items === 1);

$cart->removeBook($testBook->id);
test('Remove book from cart', !$cart->hasBook($testBook->id));

// ==================== ORDER TESTS ====================
echo "\n--- ORDER TESTS ---\n";

$cart->addBook($testBook->id);
$order = Order::createFromCart($cart);

test('Order created', $order->exists);
test('Order has correct total_items', $order->total_items === 1);
test('Cart cleared after checkout', $cart->fresh()->items()->count() === 0);
test('User has ordered book', $testUser->hasOrderedBook($testBook->id));

// ==================== COLLECTION TESTS ====================
echo "\n--- COLLECTION TESTS ---\n";

$collection = $testUser->collections()->where('name', 'Reading')->first();
test('Reading collection exists', $collection !== null);

$collection->addBook($testBook->id);
test('Add book to collection', $collection->hasBook($testBook->id));

$collection->removeBook($testBook->id);
test('Remove book from collection', !$collection->hasBook($testBook->id));

// ==================== READING PROGRESS TESTS ====================
echo "\n--- READING PROGRESS TESTS ---\n";

$progress = ReadingProgress::getOrCreate($testUser->id, $testBook->id);
test('Reading progress created', $progress->exists);

$progress->updateProgress(50, 100);
test('Progress updated to 50%', $progress->progress_percentage == 50);
test('Last page is 50', $progress->last_page === 50);

$progress->updateProgress(100, 100);
test('Progress updated to 100%', $progress->progress_percentage == 100);

// ==================== REVIEW TESTS ====================
echo "\n--- REVIEW TESTS ---\n";

$review = Review::create([
  'user_id' => $testUser->id,
  'book_id' => $testBook->id,
  'rating' => 5,
  'review_text' => 'Great book!',
]);

test('Review created', $review->exists);
test('Review rating is 5', $review->rating === 5);

$testBook->refresh();
test('Book average rating updated', $testBook->average_rating == 5.00);

// ==================== USER PREFERENCE TESTS ====================
echo "\n--- USER PREFERENCE TESTS ---\n";

$prefs = $testUser->preferences;
test('Preferences exist', $prefs !== null);
test('Default theme is light', $prefs->theme === 'light');
test('Default font_size is 16', $prefs->font_size === 16);

$prefs->update(['theme' => 'dark', 'font_size' => 20]);
$prefs->refresh();
test('Theme updated to dark', $prefs->theme === 'dark');
test('Font size updated to 20', $prefs->font_size === 20);

// ==================== ADMIN TESTS ====================
echo "\n--- ADMIN TESTS ---\n";

$admin = User::where('email', 'admin@bookreader.com')->first();
test('Admin user exists', $admin !== null);
test('Admin role is correct', $admin->role === 'admin');
test('Admin isAdmin() returns true', $admin->isAdmin());
test('Regular user isAdmin() returns false', !$testUser->isAdmin());

// ==================== BOOK STATUS TESTS ====================
echo "\n--- BOOK STATUS TESTS ---\n";

$pendingBook = Book::create([
  'title' => 'Pending Book',
  'author' => 'Author',
  'category_id' => $category->id,
  'file_type' => 'pdf',
  'status' => 'pending',
  'created_by' => $testUser->id,
]);

test('Book status is pending', $pendingBook->isPending());
test('Pending book not in approved scope', Book::approved()->where('id', $pendingBook->id)->count() === 0);

$pendingBook->approve();
test('Book approved', $pendingBook->isApproved());

$pendingBook->reject('Test reason');
test('Book rejected', $pendingBook->isRejected());
test('Rejection reason saved', $pendingBook->rejection_reason === 'Test reason');

// ==================== READING STATS TESTS ====================
echo "\n--- READING STATS TESTS ---\n";

$stats = $testUser->reading_stats;
test('Reading stats has total_books_downloaded', isset($stats['total_books_downloaded']));
test('Reading stats has books_currently_reading', isset($stats['books_currently_reading']));
test('Reading stats has books_completed', isset($stats['books_completed']));
test('Reading stats has reviews_written', isset($stats['reviews_written']));

// ==================== CLEANUP ====================
echo "\n--- CLEANUP ---\n";

$review->delete();
$progress->delete();
$testBook->forceDelete();
$pendingBook->forceDelete();
$testUser->cart()->delete();
$testUser->collections()->delete();
$testUser->preferences()->delete();
$testUser->orders()->each(fn($o) => $o->items()->delete());
$testUser->orders()->delete();
$testUser->forceDelete();

test('Cleanup complete', true);

// ==================== SUMMARY ====================
echo "\n========================================\n";
echo "  TEST SUMMARY\n";
echo "========================================\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";
echo "Total:  " . ($passed + $failed) . "\n";
echo "========================================\n";

if ($failed > 0) {
  echo "❌ SOME TESTS FAILED!\n";
} else {
  echo "✅ ALL TESTS PASSED!\n";
}
