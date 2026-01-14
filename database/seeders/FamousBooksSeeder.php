<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class FamousBooksSeeder extends Seeder
{
  public function run(): void
  {
    // Get category IDs
    $categories = Category::pluck('id', 'name')->toArray();

    // Famous books data - 50 books total (25 free, 25 paid)
    $books = [
      // === FICTION (4 books) ===
      [
        'title' => 'Pride and Prejudice',
        'author' => 'Jane Austen',
        'category' => 'Fiction',
        'description' => 'A romantic novel following Elizabeth Bennet as she deals with issues of manners, upbringing, morality, and love in early 19th-century England.',
        'pages' => 279,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1342/1342-pdf.pdf',
      ],
      [
        'title' => 'Great Expectations',
        'author' => 'Charles Dickens',
        'category' => 'Fiction',
        'description' => 'The story of the orphan Pip, writing his life from his early days as a child until adulthood.',
        'pages' => 544,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439563-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1400/1400-pdf.pdf',
      ],
      [
        'title' => 'The Great Gatsby',
        'author' => 'F. Scott Fitzgerald',
        'category' => 'Fiction',
        'description' => 'A story of wealth, love, and tragedy set in the Jazz Age of the 1920s.',
        'pages' => 180,
        'price' => 4.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg',
        'file' => 'https://www.gutenberg.org/files/64317/64317-pdf.pdf',
      ],
      [
        'title' => 'Little Women',
        'author' => 'Louisa May Alcott',
        'category' => 'Fiction',
        'description' => 'The story of the four March sisters as they grow from childhood to adulthood.',
        'pages' => 449,
        'price' => 3.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780147514011-L.jpg',
        'file' => 'https://www.gutenberg.org/files/514/514-pdf.pdf',
      ],

      // === SCIENCE FICTION (4 books) ===
      [
        'title' => 'Frankenstein',
        'author' => 'Mary Shelley',
        'category' => 'Science Fiction',
        'description' => 'A scientist creates a sapient creature in an unorthodox scientific experiment.',
        'pages' => 280,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780486282114-L.jpg',
        'file' => 'https://www.gutenberg.org/files/84/84-pdf.pdf',
      ],
      [
        'title' => 'The Time Machine',
        'author' => 'H.G. Wells',
        'category' => 'Science Fiction',
        'description' => 'A scientist travels to the far future and discovers the fate of humanity.',
        'pages' => 118,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780451530707-L.jpg',
        'file' => 'https://www.gutenberg.org/files/35/35-pdf.pdf',
      ],
      [
        'title' => 'The War of the Worlds',
        'author' => 'H.G. Wells',
        'category' => 'Science Fiction',
        'description' => 'Martians invade Earth in this classic science fiction tale.',
        'pages' => 192,
        'price' => 2.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780451530653-L.jpg',
        'file' => 'https://www.gutenberg.org/files/36/36-pdf.pdf',
      ],
      [
        'title' => 'Twenty Thousand Leagues Under the Sea',
        'author' => 'Jules Verne',
        'category' => 'Science Fiction',
        'description' => 'Captain Nemo and his submarine Nautilus take readers on an underwater adventure.',
        'pages' => 320,
        'price' => 5.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780553212525-L.jpg',
        'file' => 'https://www.gutenberg.org/files/164/164-pdf.pdf',
      ],

      // === FANTASY (3 books) ===
      [
        'title' => "Alice's Adventures in Wonderland",
        'author' => 'Lewis Carroll',
        'category' => 'Fantasy',
        'description' => 'Alice falls down a rabbit hole into a fantasy world of strange creatures.',
        'pages' => 96,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439761-L.jpg',
        'file' => 'https://www.gutenberg.org/files/11/11-pdf.pdf',
      ],
      [
        'title' => 'Peter Pan',
        'author' => 'J.M. Barrie',
        'category' => 'Fantasy',
        'description' => 'The boy who never grows up takes the Darling children to Neverland.',
        'pages' => 200,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141322575-L.jpg',
        'file' => 'https://www.gutenberg.org/files/16/16-pdf.pdf',
      ],
      [
        'title' => 'The Wonderful Wizard of Oz',
        'author' => 'L. Frank Baum',
        'category' => 'Fantasy',
        'description' => "Dorothy is swept away to the magical land of Oz.",
        'pages' => 152,
        'price' => 3.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780486206912-L.jpg',
        'file' => 'https://www.gutenberg.org/files/55/55-pdf.pdf',
      ],

      // === MYSTERY (3 books) ===
      [
        'title' => 'The Adventures of Sherlock Holmes',
        'author' => 'Arthur Conan Doyle',
        'category' => 'Mystery',
        'description' => 'Twelve short stories featuring the famous detective Sherlock Holmes.',
        'pages' => 307,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141034355-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1661/1661-pdf.pdf',
      ],
      [
        'title' => 'The Moonstone',
        'author' => 'Wilkie Collins',
        'category' => 'Mystery',
        'description' => 'A precious diamond brings misfortune to those who possess it.',
        'pages' => 528,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140434088-L.jpg',
        'file' => 'https://www.gutenberg.org/files/155/155-pdf.pdf',
      ],
      [
        'title' => 'The Thirty-Nine Steps',
        'author' => 'John Buchan',
        'category' => 'Mystery',
        'description' => 'An adventure novel featuring a man on the run from spies.',
        'pages' => 100,
        'price' => 4.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141441177-L.jpg',
        'file' => 'https://www.gutenberg.org/files/558/558-pdf.pdf',
      ],

      // === ROMANCE (3 books) ===
      [
        'title' => 'Jane Eyre',
        'author' => 'Charlotte Brontë',
        'category' => 'Romance',
        'description' => 'An orphaned governess falls in love with her mysterious employer.',
        'pages' => 507,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141441146-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1260/1260-pdf.pdf',
      ],
      [
        'title' => 'Wuthering Heights',
        'author' => 'Emily Brontë',
        'category' => 'Romance',
        'description' => 'A wild, passionate tale of the doomed love between Heathcliff and Catherine.',
        'pages' => 416,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439556-L.jpg',
        'file' => 'https://www.gutenberg.org/files/768/768-pdf.pdf',
      ],
      [
        'title' => 'Emma',
        'author' => 'Jane Austen',
        'category' => 'Romance',
        'description' => 'A comedy about a young woman who meddles in matchmaking.',
        'pages' => 474,
        'price' => 3.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439587-L.jpg',
        'file' => 'https://www.gutenberg.org/files/158/158-pdf.pdf',
      ],

      // === BIOGRAPHY (3 books) ===
      [
        'title' => 'The Autobiography of Benjamin Franklin',
        'author' => 'Benjamin Franklin',
        'category' => 'Biography',
        'description' => 'The life story of one of America\'s founding fathers.',
        'pages' => 143,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780486290737-L.jpg',
        'file' => 'https://www.gutenberg.org/files/20203/20203-pdf.pdf',
      ],
      [
        'title' => 'Narrative of the Life of Frederick Douglass',
        'author' => 'Frederick Douglass',
        'category' => 'Biography',
        'description' => 'A memoir of an escaped slave who became an abolitionist leader.',
        'pages' => 128,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780486284996-L.jpg',
        'file' => 'https://www.gutenberg.org/files/23/23-pdf.pdf',
      ],
      [
        'title' => 'Up from Slavery',
        'author' => 'Booker T. Washington',
        'category' => 'Biography',
        'description' => 'The autobiography of the influential African-American educator.',
        'pages' => 208,
        'price' => 5.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780486287386-L.jpg',
        'file' => 'https://www.gutenberg.org/files/2376/2376-pdf.pdf',
      ],

      // === SELF-HELP (3 books) ===
      [
        'title' => 'The Art of War',
        'author' => 'Sun Tzu',
        'category' => 'Self-Help',
        'description' => 'Ancient Chinese treatise on military strategy and tactics.',
        'pages' => 68,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9781590302255-L.jpg',
        'file' => 'https://www.gutenberg.org/files/132/132-pdf.pdf',
      ],
      [
        'title' => 'Meditations',
        'author' => 'Marcus Aurelius',
        'category' => 'Self-Help',
        'description' => 'Stoic philosophical writings of the Roman Emperor.',
        'pages' => 254,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140449334-L.jpg',
        'file' => 'https://www.gutenberg.org/files/55317/55317-pdf.pdf',
      ],
      [
        'title' => 'Self-Reliance',
        'author' => 'Ralph Waldo Emerson',
        'category' => 'Self-Help',
        'description' => 'Essays on individualism and nonconformity.',
        'pages' => 96,
        'price' => 2.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780486277905-L.jpg',
        'file' => 'https://www.gutenberg.org/files/16643/16643-pdf.pdf',
      ],

      // === HISTORY (4 books) ===
      [
        'title' => 'The History of the Decline and Fall of the Roman Empire',
        'author' => 'Edward Gibbon',
        'category' => 'History',
        'description' => 'A monumental historical work covering the fall of Rome.',
        'pages' => 3800,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140433937-L.jpg',
        'file' => 'https://www.gutenberg.org/files/25717/25717-pdf.pdf',
      ],
      [
        'title' => 'The Prince',
        'author' => 'Niccolò Machiavelli',
        'category' => 'History',
        'description' => 'A 16th-century political treatise on how to acquire and maintain power.',
        'pages' => 140,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140447064-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1232/1232-pdf.pdf',
      ],
      [
        'title' => 'Common Sense',
        'author' => 'Thomas Paine',
        'category' => 'History',
        'description' => 'The pamphlet that inspired American independence.',
        'pages' => 52,
        'price' => 1.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780143036258-L.jpg',
        'file' => 'https://www.gutenberg.org/files/147/147-pdf.pdf',
      ],
      [
        'title' => 'The Federalist Papers',
        'author' => 'Alexander Hamilton, James Madison, John Jay',
        'category' => 'History',
        'description' => 'Essays promoting ratification of the United States Constitution.',
        'pages' => 584,
        'price' => 6.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780451528810-L.jpg',
        'file' => 'https://www.gutenberg.org/files/18/18-pdf.pdf',
      ],

      // === TECHNOLOGY (3 books) ===
      [
        'title' => 'On the Origin of Species',
        'author' => 'Charles Darwin',
        'category' => 'Technology',
        'description' => 'The foundational work introducing the theory of evolution.',
        'pages' => 502,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780451529060-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1228/1228-pdf.pdf',
      ],
      [
        'title' => 'The Interpretation of Dreams',
        'author' => 'Sigmund Freud',
        'category' => 'Technology',
        'description' => 'Freud\'s groundbreaking work on dream analysis and the unconscious mind.',
        'pages' => 640,
        'price' => 7.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780465019779-L.jpg',
        'file' => 'https://www.gutenberg.org/files/63090/63090-pdf.pdf',
      ],
      [
        'title' => 'Relativity: The Special and General Theory',
        'author' => 'Albert Einstein',
        'category' => 'Technology',
        'description' => 'Einstein explains his revolutionary theories in accessible terms.',
        'pages' => 168,
        'price' => 4.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780486417141-L.jpg',
        'file' => 'https://www.gutenberg.org/files/5001/5001-pdf.pdf',
      ],

      // === PHILOSOPHY (4 books) ===
      [
        'title' => 'The Republic',
        'author' => 'Plato',
        'category' => 'Philosophy',
        'description' => 'Plato\'s most famous work on justice and the ideal state.',
        'pages' => 416,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140449143-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1497/1497-pdf.pdf',
      ],
      [
        'title' => 'Beyond Good and Evil',
        'author' => 'Friedrich Nietzsche',
        'category' => 'Philosophy',
        'description' => 'Nietzsche challenges traditional morality and philosophy.',
        'pages' => 240,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140449235-L.jpg',
        'file' => 'https://www.gutenberg.org/files/4363/4363-pdf.pdf',
      ],
      [
        'title' => 'Thus Spoke Zarathustra',
        'author' => 'Friedrich Nietzsche',
        'category' => 'Philosophy',
        'description' => 'A philosophical novel about the prophet Zarathustra.',
        'pages' => 352,
        'price' => 4.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140441185-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1998/1998-pdf.pdf',
      ],
      [
        'title' => 'Critique of Pure Reason',
        'author' => 'Immanuel Kant',
        'category' => 'Philosophy',
        'description' => 'Kant\'s foundational work in modern philosophy.',
        'pages' => 856,
        'price' => 8.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140447477-L.jpg',
        'file' => 'https://www.gutenberg.org/files/4280/4280-pdf.pdf',
      ],

      // === POETRY (3 books) ===
      [
        'title' => 'Leaves of Grass',
        'author' => 'Walt Whitman',
        'category' => 'Poetry',
        'description' => 'A groundbreaking poetry collection celebrating American democracy.',
        'pages' => 400,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140421996-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1322/1322-pdf.pdf',
      ],
      [
        'title' => 'The Canterbury Tales',
        'author' => 'Geoffrey Chaucer',
        'category' => 'Poetry',
        'description' => 'A collection of stories told by pilgrims traveling to Canterbury.',
        'pages' => 504,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140424386-L.jpg',
        'file' => 'https://www.gutenberg.org/files/2383/2383-pdf.pdf',
      ],
      [
        'title' => 'The Divine Comedy',
        'author' => 'Dante Alighieri',
        'category' => 'Poetry',
        'description' => 'An epic poem describing the journey through Hell, Purgatory, and Heaven.',
        'pages' => 798,
        'price' => 5.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140448955-L.jpg',
        'file' => 'https://www.gutenberg.org/files/8800/8800-pdf.pdf',
      ],

      // === DRAMA (3 books) ===
      [
        'title' => 'Romeo and Juliet',
        'author' => 'William Shakespeare',
        'category' => 'Drama',
        'description' => 'The tragic love story of two young star-crossed lovers.',
        'pages' => 128,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780743477116-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1513/1513-pdf.pdf',
      ],
      [
        'title' => 'Hamlet',
        'author' => 'William Shakespeare',
        'category' => 'Drama',
        'description' => 'The Prince of Denmark seeks revenge for his father\'s murder.',
        'pages' => 144,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780743477123-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1524/1524-pdf.pdf',
      ],
      [
        'title' => 'A Midsummer Night\'s Dream',
        'author' => 'William Shakespeare',
        'category' => 'Drama',
        'description' => 'A comedy about love, magic, and fairies.',
        'pages' => 112,
        'price' => 2.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780743477543-L.jpg',
        'file' => 'https://www.gutenberg.org/files/1514/1514-pdf.pdf',
      ],

      // === CHILDREN (3 books) ===
      [
        'title' => "Aesop's Fables",
        'author' => 'Aesop',
        'category' => 'Children',
        'description' => 'A collection of fables credited to the Greek storyteller Aesop.',
        'pages' => 200,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140447637-L.jpg',
        'file' => 'https://www.gutenberg.org/files/28/28-pdf.pdf',
      ],
      [
        'title' => "Grimm's Fairy Tales",
        'author' => 'Brothers Grimm',
        'category' => 'Children',
        'description' => 'Classic fairy tales including Cinderella, Snow White, and more.',
        'pages' => 512,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140449846-L.jpg',
        'file' => 'https://www.gutenberg.org/files/2591/2591-pdf.pdf',
      ],
      [
        'title' => 'The Tale of Peter Rabbit',
        'author' => 'Beatrix Potter',
        'category' => 'Children',
        'description' => 'The mischievous rabbit ventures into Mr. McGregor\'s garden.',
        'pages' => 72,
        'price' => 1.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780723247708-L.jpg',
        'file' => 'https://www.gutenberg.org/files/14838/14838-pdf.pdf',
      ],

      // === YOUNG ADULT (4 books) ===
      [
        'title' => 'Treasure Island',
        'author' => 'Robert Louis Stevenson',
        'category' => 'Young Adult',
        'description' => 'A classic adventure tale of pirates and buried treasure.',
        'pages' => 292,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141321004-L.jpg',
        'file' => 'https://www.gutenberg.org/files/120/120-pdf.pdf',
      ],
      [
        'title' => 'The Jungle Book',
        'author' => 'Rudyard Kipling',
        'category' => 'Young Adult',
        'description' => 'Stories of Mowgli, a boy raised by wolves in the Indian jungle.',
        'pages' => 277,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141325295-L.jpg',
        'file' => 'https://www.gutenberg.org/files/236/236-pdf.pdf',
      ],
      [
        'title' => 'The Swiss Family Robinson',
        'author' => 'Johann David Wyss',
        'category' => 'Young Adult',
        'description' => 'A family is shipwrecked and must survive on a deserted island.',
        'pages' => 352,
        'price' => 4.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140621341-L.jpg',
        'file' => 'https://www.gutenberg.org/files/3836/3836-pdf.pdf',
      ],
      [
        'title' => 'The Call of the Wild',
        'author' => 'Jack London',
        'category' => 'Young Adult',
        'description' => 'A domesticated dog becomes wild in the Alaskan wilderness.',
        'pages' => 128,
        'price' => 2.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780141321059-L.jpg',
        'file' => 'https://www.gutenberg.org/files/215/215-pdf.pdf',
      ],

      // === NON-FICTION (3 books) ===
      [
        'title' => 'Walden',
        'author' => 'Henry David Thoreau',
        'category' => 'Non-Fiction',
        'description' => 'Reflections on simple living in natural surroundings.',
        'pages' => 352,
        'price' => 0,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780486284958-L.jpg',
        'file' => 'https://www.gutenberg.org/files/205/205-pdf.pdf',
      ],
      [
        'title' => 'On Liberty',
        'author' => 'John Stuart Mill',
        'category' => 'Non-Fiction',
        'description' => 'A classic work on the nature and limits of political freedom.',
        'pages' => 128,
        'price' => 3.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140432077-L.jpg',
        'file' => 'https://www.gutenberg.org/files/34901/34901-pdf.pdf',
      ],
      [
        'title' => 'The Communist Manifesto',
        'author' => 'Karl Marx and Friedrich Engels',
        'category' => 'Non-Fiction',
        'description' => 'The foundational text of communist political theory.',
        'pages' => 68,
        'price' => 1.99,
        'cover' => 'https://covers.openlibrary.org/b/isbn/9780140447576-L.jpg',
        'file' => 'https://www.gutenberg.org/files/61/61-pdf.pdf',
      ],
    ];

    // Create books
    foreach ($books as $bookData) {
      $categoryId = $categories[$bookData['category']] ?? 1;

      Book::updateOrCreate(
        ['title' => $bookData['title']],
        [
          'author' => $bookData['author'],
          'category_id' => $categoryId,
          'description' => $bookData['description'],
          'number_of_pages' => $bookData['pages'],
          'price' => $bookData['price'],
          'cover_image' => $bookData['cover'],
          'cover_type' => 'url',
          'file_url' => $bookData['file'],
          'file_type' => 'pdf',
          'status' => 'approved',
          'created_by' => 1, // Admin
        ]
      );
    }

    $freeCount = collect($books)->where('price', 0)->count();
    $paidCount = collect($books)->where('price', '>', 0)->count();

    echo "Seeded {$freeCount} free books and {$paidCount} paid books (Total: " . count($books) . ")\n";
  }
}
