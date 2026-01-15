<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
  public function run(): void
  {
    // Get or create categories
    $categories = [
      'Programming' => Category::firstOrCreate(['name' => 'Programming']),
      'Fiction' => Category::firstOrCreate(['name' => 'Fiction']),
      'Science' => Category::firstOrCreate(['name' => 'Science']),
      'History' => Category::firstOrCreate(['name' => 'History']),
      'Self-Help' => Category::firstOrCreate(['name' => 'Self-Help']),
      'Biography' => Category::firstOrCreate(['name' => 'Biography']),
      'Children' => Category::firstOrCreate(['name' => 'Children']),
      'Drama' => Category::firstOrCreate(['name' => 'Drama']),
    ];

    // Get admin user or first user as creator
    $admin = User::where('role', 'admin')->first() ?? User::first();
    $createdBy = $admin ? $admin->id : 1;

    // Valid sample PDF URLs (verified working)
    // Using W3C and Mozilla sample PDFs
    $samplePdfUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';

    // Sample books with Open Library cover images
    $books = [
      [
        'title' => 'JavaScript: The Good Parts',
        'author' => 'Douglas Crockford',
        'description' => 'Most programming languages contain good and bad parts, but JavaScript has more than its share of the bad.',
        'category_id' => $categories['Programming']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 176,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/0596517742-L.jpg',
        'file_url' => $samplePdfUrl,
      ],
      [
        'title' => 'The Great Gatsby',
        'author' => 'F. Scott Fitzgerald',
        'description' => 'A story of the mysteriously wealthy Jay Gatsby and his love for Daisy Buchanan.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 180,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg',
        'file_url' => $samplePdfUrl,
      ],
      [
        'title' => 'A Brief History of Time',
        'author' => 'Stephen Hawking',
        'description' => 'A landmark volume in science writing by one of the great minds of our time.',
        'category_id' => $categories['Science']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 256,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/0553380168-L.jpg',
        'file_url' => $samplePdfUrl,
      ],
      [
        'title' => 'Sapiens: A Brief History of Humankind',
        'author' => 'Yuval Noah Harari',
        'description' => 'A groundbreaking narrative of humanity creation and evolution.',
        'category_id' => $categories['History']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 443,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/0062316095-L.jpg',
        'file_url' => $samplePdfUrl,
      ],
      [
        'title' => 'Atomic Habits',
        'author' => 'James Clear',
        'description' => 'An easy and proven way to build good habits and break bad ones.',
        'category_id' => $categories['Self-Help']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 320,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/0735211299-L.jpg',
        'file_url' => $samplePdfUrl,
      ],
      [
        'title' => 'Clean Code',
        'author' => 'Robert C. Martin',
        'description' => 'A handbook of agile software craftsmanship.',
        'category_id' => $categories['Programming']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 464,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/0132350882-L.jpg',
        'file_url' => $samplePdfUrl,
      ],
      [
        'title' => 'The Pragmatic Programmer',
        'author' => 'David Thomas & Andrew Hunt',
        'description' => 'Your journey to mastery in software development.',
        'category_id' => $categories['Programming']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 352,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/020161622X-L.jpg',
        'file_url' => $samplePdfUrl,
      ],
      [
        'title' => 'Steve Jobs',
        'author' => 'Walter Isaacson',
        'description' => 'The exclusive biography of Apple co-founder Steve Jobs.',
        'category_id' => $categories['Biography']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 630,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/1451648537-L.jpg',
        'file_url' => $samplePdfUrl,
      ],
    ];

    foreach ($books as $bookData) {
      Book::updateOrCreate(
        ['title' => $bookData['title'], 'author' => $bookData['author']],
        $bookData
      );
    }

    echo "Seeded " . count($books) . " sample books with covers and PDF files\n";
  }
}
