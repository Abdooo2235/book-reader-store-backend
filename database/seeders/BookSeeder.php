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
    // Get or create categories first
    $categories = [
      'Programming' => Category::firstOrCreate(['name' => 'Programming']),
      'Fiction' => Category::firstOrCreate(['name' => 'Fiction']),
      'Science' => Category::firstOrCreate(['name' => 'Science']),
      'History' => Category::firstOrCreate(['name' => 'History']),
      'Self-Help' => Category::firstOrCreate(['name' => 'Self-Help']),
    ];

    // Get admin user or first user as creator
    $admin = User::where('role', 'admin')->first() ?? User::first();
    $createdBy = $admin ? $admin->id : 1;

    // Sample books data
    $books = [
      [
        'title' => 'Learn Flutter Development',
        'author' => 'John Doe',
        'description' => 'A comprehensive guide to building mobile apps with Flutter and Dart.',
        'category_id' => $categories['Programming']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 350,
        'status' => 'approved',
        'created_by' => $createdBy,
      ],
      [
        'title' => 'JavaScript: The Good Parts',
        'author' => 'Douglas Crockford',
        'description' => 'Most programming languages contain good and bad parts, but JavaScript has more than its share of the bad.',
        'category_id' => $categories['Programming']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 176,
        'status' => 'approved',
        'created_by' => $createdBy,
      ],
      [
        'title' => 'The Great Gatsby',
        'author' => 'F. Scott Fitzgerald',
        'description' => 'A story of the mysteriously wealthy Jay Gatsby and his love for Daisy Buchanan.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'epub',
        'number_of_pages' => 180,
        'status' => 'approved',
        'created_by' => $createdBy,
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
      ],
    ];

    foreach ($books as $bookData) {
      Book::firstOrCreate(
        ['title' => $bookData['title'], 'author' => $bookData['author']],
        $bookData
      );
    }

    echo "Seeded " . count($books) . " sample books\n";
  }
}
