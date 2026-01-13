<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
  public function run(): void
  {
    $categories = [
      'Fiction',
      'Non-Fiction',
      'Science Fiction',
      'Fantasy',
      'Mystery',
      'Romance',
      'Biography',
      'Self-Help',
      'History',
      'Technology',
      'Philosophy',
      'Poetry',
      'Drama',
      'Children',
      'Young Adult',
    ];

    foreach ($categories as $name) {
      Category::firstOrCreate(['name' => $name]);
    }
  }
}
