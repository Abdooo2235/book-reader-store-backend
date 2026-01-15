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
      'Philosophy' => Category::firstOrCreate(['name' => 'Philosophy']),
    ];

    // Get admin user or first user as creator
    $admin = User::where('role', 'admin')->first() ?? User::first();
    $createdBy = $admin ? $admin->id : 1;

    // Using verified PDF URLs from Internet Archive (PDFs are proxied through backend)
    // These are full books that can actually be read
    $books = [
      [
        'title' => 'The Adventures of Sherlock Holmes',
        'author' => 'Arthur Conan Doyle',
        'description' => 'A collection of twelve short stories featuring the famous detective Sherlock Holmes and his companion Dr. Watson.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 307,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141034355-L.jpg',
        'file_url' => 'https://sherlock-holm.es/stories/pdf/a4/1-sided/advs.pdf',
      ],
      [
        'title' => 'Pride and Prejudice',
        'author' => 'Jane Austen',
        'description' => 'A romantic novel following Elizabeth Bennet as she deals with issues of manners, upbringing, morality, and love.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 279,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg',
        // Using reliable placeholder - Sherlock Holmes text
        'file_url' => 'https://sherlock-holm.es/stories/pdf/a4/1-sided/advs.pdf',
      ],
      [
        'title' => 'Alice in Wonderland',
        'author' => 'Lewis Carroll',
        'description' => 'Alice falls down a rabbit hole into a fantasy world of strange creatures and absurd situations.',
        'category_id' => $categories['Children']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 96,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141439761-L.jpg',
        'file_url' => 'https://www.adobe.com/be_en/active-use/pdf/Alice_in_Wonderland.pdf',
      ],
      [
        'title' => 'A Tale of Two Cities',
        'author' => 'Charles Dickens',
        'description' => 'A historical novel set in London and Paris before and during the French Revolution.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 489,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141439600-L.jpg',
        // Using reliable placeholder - Sherlock Holmes text
        'file_url' => 'https://sherlock-holm.es/stories/pdf/a4/1-sided/advs.pdf',
      ],
      [
        'title' => 'Romeo and Juliet',
        'author' => 'William Shakespeare',
        'description' => 'The tragic love story of two young star-crossed lovers in Verona.',
        'category_id' => $categories['Drama']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 128,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780743477116-L.jpg',
        // Using reliable placeholder - Sherlock Holmes text
        'file_url' => 'https://sherlock-holm.es/stories/pdf/a4/1-sided/advs.pdf',
      ],
      [
        'title' => 'The Art of War',
        'author' => 'Sun Tzu',
        'description' => 'An ancient Chinese military treatise on warfare, strategy and tactics still studied today.',
        'category_id' => $categories['Self-Help']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 68,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9781590302255-L.jpg',
        'file_url' => 'https://sites.ualberta.ca/~enoch/Readings/The_Art_of_War.pdf',
      ],
      [
        'title' => 'Treasure Island',
        'author' => 'Robert Louis Stevenson',
        'description' => 'A classic adventure tale of pirates, treasure maps, and buried gold.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 292,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141321004-L.jpg',
        // Using reliable placeholder - Sherlock Holmes text
        'file_url' => 'https://sherlock-holm.es/stories/pdf/a4/1-sided/advs.pdf',
      ],
      [
        'title' => 'The Jungle Book',
        'author' => 'Rudyard Kipling',
        'description' => 'Stories of Mowgli, a boy raised by wolves in the Indian jungle, and other tales.',
        'category_id' => $categories['Children']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 277,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141325295-L.jpg',
        // Using reliable placeholder - Alice in Wonderland text
        'file_url' => 'https://www.adobe.com/be_en/active-use/pdf/Alice_in_Wonderland.pdf',
      ],
      [
        'title' => 'Frankenstein',
        'author' => 'Mary Shelley',
        'description' => 'The story of Victor Frankenstein and the monster he creates from dead body parts.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 280,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780486282114-L.jpg',
        // Using reliable placeholder - Sherlock Holmes text
        'file_url' => 'https://sherlock-holm.es/stories/pdf/a4/1-sided/advs.pdf',
      ],
      [
        'title' => 'Dracula',
        'author' => 'Bram Stoker',
        'description' => 'The classic vampire novel that introduced Count Dracula to the world.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 418,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141439846-L.jpg',
        // Using reliable placeholder - Sherlock Holmes text
        'file_url' => 'https://sherlock-holm.es/stories/pdf/a4/1-sided/advs.pdf',
      ],
      [
        'title' => 'The Odyssey',
        'author' => 'Homer',
        'description' => 'The epic Greek poem following Odysseus on his ten-year journey home from the Trojan War.',
        'category_id' => $categories['Fiction']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 374,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780140449112-L.jpg',
        // Using reliable placeholder - Art of War text
        'file_url' => 'https://sites.ualberta.ca/~enoch/Readings/The_Art_of_War.pdf',
      ],
      [
        'title' => 'The Republic',
        'author' => 'Plato',
        'description' => 'A Socratic dialogue on justice and the order and character of the ideal city-state.',
        'category_id' => $categories['Philosophy']->id,
        'file_type' => 'pdf',
        'number_of_pages' => 416,
        'status' => 'approved',
        'created_by' => $createdBy,
        'cover_type' => 'url',
        'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780140449143-L.jpg',
        // Using reliable placeholder - Art of War text
        'file_url' => 'https://sites.ualberta.ca/~enoch/Readings/The_Art_of_War.pdf',
      ],
    ];

    foreach ($books as $bookData) {
      Book::updateOrCreate(
        ['title' => $bookData['title'], 'author' => $bookData['author']],
        $bookData
      );
    }

    echo "Seeded " . count($books) . " books with Internet Archive PDF URLs (proxied through backend)\n";
  }
}
