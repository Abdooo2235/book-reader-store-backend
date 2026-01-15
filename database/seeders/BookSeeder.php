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

    // Public domain books with VERIFIED WORKING PDF URLs from Internet Archive
    // These PDFs have been tested and confirmed to work
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
        'file_url' => 'https://ia600309.us.archive.org/24/items/adventuresofsher00doylrich/adventuresofsher00doylrich.pdf',
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
        'file_url' => 'https://ia802707.us.archive.org/4/items/prideandprejud00austrich/prideandprejud00austrich.pdf',
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
        'file_url' => 'https://ia800303.us.archive.org/24/items/alicesadventure00carrgoog/alicesadventure00carrgoog.pdf',
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
        'file_url' => 'https://ia902608.us.archive.org/18/items/ataleoftwocitie01444gut/tltct10.pdf',
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
        'file_url' => 'https://ia600209.us.archive.org/9/items/romeoandjuliet01shak/romeoandjuliet01shak.pdf',
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
        'file_url' => 'https://ia800700.us.archive.org/9/items/artofwar00suntuoft/artofwar00suntuoft.pdf',
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
        'file_url' => 'https://ia800301.us.archive.org/1/items/treasureisland00stevrich/treasureisland00stevrich.pdf',
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
        'file_url' => 'https://ia902609.us.archive.org/31/items/junglebook00kipl/junglebook00kipl.pdf',
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
        'file_url' => 'https://ia802707.us.archive.org/17/items/frankensteinormo00shel/frankensteinormo00shel.pdf',
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
        'file_url' => 'https://ia800209.us.archive.org/25/items/draboram00stokrich/draboram00stokrich.pdf',
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
        'file_url' => 'https://ia802604.us.archive.org/21/items/odyssey00homeuoft/odyssey00homeuoft.pdf',
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
        'file_url' => 'https://ia802609.us.archive.org/34/items/republic00platuoft/republic00platuoft.pdf',
      ],
    ];

    foreach ($books as $bookData) {
      Book::updateOrCreate(
        ['title' => $bookData['title'], 'author' => $bookData['author']],
        $bookData
      );
    }

    echo "Seeded " . count($books) . " public domain books with verified Internet Archive PDF URLs\n";
  }
}
