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
                'file' => 'https://www.planetebook.com/free-ebooks/pride-and-prejudice.pdf',
            ],
            [
                'title' => 'Great Expectations',
                'author' => 'Charles Dickens',
                'category' => 'Fiction',
                'description' => 'The story of the orphan Pip, writing his life from his early days as a child until adulthood.',
                'pages' => 544,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439563-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/great-expectations.pdf',
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'category' => 'Fiction',
                'description' => 'A story of wealth, love, and tragedy set in the Jazz Age of the 1920s.',
                'pages' => 180,
                'price' => 4.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/the-great-gatsby.pdf',
            ],
            [
                'title' => 'Little Women',
                'author' => 'Louisa May Alcott',
                'category' => 'Fiction',
                'description' => 'The story of the four March sisters as they grow from childhood to adulthood.',
                'pages' => 449,
                'price' => 3.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780147514011-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/little-women.pdf',
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
                'file' => 'https://www.planetebook.com/free-ebooks/frankenstein.pdf',
            ],
            [
                'title' => 'The Time Machine',
                'author' => 'H.G. Wells',
                'category' => 'Science Fiction',
                'description' => 'A scientist travels to the far future and discovers the fate of humanity.',
                'pages' => 118,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780451530707-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/the-time-machine.pdf',
            ],
            [
                'title' => 'The War of the Worlds',
                'author' => 'H.G. Wells',
                'category' => 'Science Fiction',
                'description' => 'Martians invade Earth in this classic science fiction tale.',
                'pages' => 192,
                'price' => 2.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780451530653-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/the-war-of-the-worlds.pdf',
            ],
            [
                'title' => 'Twenty Thousand Leagues Under the Sea',
                'author' => 'Jules Verne',
                'category' => 'Science Fiction',
                'description' => 'Captain Nemo and his submarine Nautilus take readers on an underwater adventure.',
                'pages' => 320,
                'price' => 5.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780553212525-L.jpg',
                'file' => 'https://archive.org/download/bub_gb_470XAAAAYAAJ/bub_gb_470XAAAAYAAJ.pdf',
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
                'file' => 'https://www.planetebook.com/free-ebooks/alices-adventures-in-wonderland.pdf',
            ],
            [
                'title' => 'Peter Pan',
                'author' => 'J.M. Barrie',
                'category' => 'Fantasy',
                'description' => 'The boy who never grows up takes the Darling children to Neverland.',
                'pages' => 200,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780141322575-L.jpg',
                'file' => 'https://archive.org/download/peter-pan-and-wendy/Peter%20Pan%20and%20Wendy.pdf',
            ],
            [
                'title' => 'The Wonderful Wizard of Oz',
                'author' => 'L. Frank Baum',
                'category' => 'Fantasy',
                'description' => 'Dorothy is swept away to the magical land of Oz.',
                'pages' => 152,
                'price' => 3.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780486206912-L.jpg',
                'file' => 'https://archive.org/download/the-wonderful-wizard-of-oz-pdfdrive.com/the%20wonderful%20wizard%20of%20oz%20%28%20PDFDrive.com%20%29.pdf',
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
                'file' => 'https://sherlock-holm.es/stories/pdf/a4/1-sided/advs.pdf',
            ],
            [
                'title' => 'The Moonstone',
                'author' => 'Wilkie Collins',
                'category' => 'Mystery',
                'description' => 'A precious diamond brings misfortune to those who possess it.',
                'pages' => 528,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140434088-L.jpg',
                'file' => 'https://archive.org/download/moonstoneromance02coll/moonstoneromance02coll.pdf',
            ],
            [
                'title' => 'The Thirty-Nine Steps',
                'author' => 'John Buchan',
                'category' => 'Mystery',
                'description' => 'An adventure novel featuring a man on the run from spies.',
                'pages' => 100,
                'price' => 4.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780141441177-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/the-thirty-nine-steps.pdf',
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
                'file' => 'https://www.planetebook.com/free-ebooks/jane-eyre.pdf',
            ],
            [
                'title' => 'Wuthering Heights',
                'author' => 'Emily Brontë',
                'category' => 'Romance',
                'description' => 'A wild, passionate tale of the doomed love between Heathcliff and Catherine.',
                'pages' => 416,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439556-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/wuthering-heights.pdf',
            ],
            [
                'title' => 'Emma',
                'author' => 'Jane Austen',
                'category' => 'Romance',
                'description' => 'A comedy about a young woman who meddles in matchmaking.',
                'pages' => 474,
                'price' => 3.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780141439587-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/emma.pdf',
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
                'file' => 'https://archive.org/download/in.ernet.dli.2015.38959/2015.38959.The-Autobiography-Of-Benjamin-Franklin.pdf',
            ],
            [
                'title' => 'Narrative of the Life of Frederick Douglass',
                'author' => 'Frederick Douglass',
                'category' => 'Biography',
                'description' => 'A memoir of an escaped slave who became an abolitionist leader.',
                'pages' => 128,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780486284996-L.jpg',
                'file' => 'https://archive.org/download/narrativeoflifeo00dougrich/narrativeoflifeo00dougrich.pdf',
            ],
            [
                'title' => 'Up from Slavery',
                'author' => 'Booker T. Washington',
                'category' => 'Biography',
                'description' => 'The autobiography of the influential African-American educator.',
                'pages' => 208,
                'price' => 5.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780486287386-L.jpg',
                'file' => 'https://archive.org/download/in.ernet.dli.2015.2381/2015.2381.Up-From-Slavery.pdf',
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
                'file' => 'https://sites.ualberta.ca/~enoch/Readings/The_Art_of_War.pdf',
            ],
            [
                'title' => 'Meditations',
                'author' => 'Marcus Aurelius',
                'category' => 'Self-Help',
                'description' => 'Stoic philosophical writings of the Roman Emperor.',
                'pages' => 254,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140449334-L.jpg',
                'file' => 'https://archive.org/download/meditationsofmar00marc/meditationsofmar00marc.pdf',
            ],
            [
                'title' => 'Self-Reliance',
                'author' => 'Ralph Waldo Emerson',
                'category' => 'Self-Help',
                'description' => 'Essays on individualism and nonconformity.',
                'pages' => 96,
                'price' => 2.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780486277905-L.jpg',
                'file' => 'https://archive.org/download/essaysfirstseri01emergoog/essaysfirstseri01emergoog.pdf',
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
                'file' => 'https://archive.org/download/historyofdecline01gibb1/historyofdecline01gibb1.pdf',
            ],
            [
                'title' => 'The Prince',
                'author' => 'Niccolò Machiavelli',
                'category' => 'History',
                'description' => 'A 16th-century political treatise on how to acquire and maintain power.',
                'pages' => 140,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140447064-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/the-prince.pdf',
            ],
            [
                'title' => 'Common Sense',
                'author' => 'Thomas Paine',
                'category' => 'History',
                'description' => 'The pamphlet that inspired American independence.',
                'pages' => 52,
                'price' => 1.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780143036258-L.jpg',
                'file' => 'https://archive.org/download/commonsense00painrich/commonsense00painrich.pdf',
            ],
            [
                'title' => 'The Federalist Papers',
                'author' => 'Alexander Hamilton, James Madison, John Jay',
                'category' => 'History',
                'description' => 'Essays promoting ratification of the United States Constitution.',
                'pages' => 584,
                'price' => 6.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780451528810-L.jpg',
                'file' => 'https://archive.org/download/cu31924032635355/cu31924032635355.pdf',
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
                'file' => 'https://archive.org/download/cu31924052394107/cu31924052394107.pdf',
            ],
            [
                'title' => 'The Interpretation of Dreams',
                'author' => 'Sigmund Freud',
                'category' => 'Technology',
                'description' => 'Freud\'s groundbreaking work on dream analysis and the unconscious mind.',
                'pages' => 640,
                'price' => 7.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780465019779-L.jpg',
                'file' => 'https://archive.org/download/in.ernet.dli.2015.188424/2015.188424.The-Interpretation-Of-Dreams.pdf',
            ],
            [
                'title' => 'Relativity: The Special and General Theory',
                'author' => 'Albert Einstein',
                'category' => 'Technology',
                'description' => 'Einstein explains his revolutionary theories in accessible terms.',
                'pages' => 168,
                'price' => 4.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780486417141-L.jpg',
                'file' => 'https://archive.org/download/cu31924011804774/cu31924011804774.pdf',
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
                'file' => 'https://archive.org/download/republicofplato00plat/republicofplato00plat.pdf',
            ],
            [
                'title' => 'Beyond Good and Evil',
                'author' => 'Friedrich Nietzsche',
                'category' => 'Philosophy',
                'description' => 'Nietzsche challenges traditional morality and philosophy.',
                'pages' => 240,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140449235-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/beyond-good-and-evil.pdf',
            ],
            [
                'title' => 'Thus Spoke Zarathustra',
                'author' => 'Friedrich Nietzsche',
                'category' => 'Philosophy',
                'description' => 'A philosophical novel about the prophet Zarathustra.',
                'pages' => 352,
                'price' => 4.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140441185-L.jpg',
                'file' => 'https://archive.org/download/thusspakezarath01nietgoog/thusspakezarath01nietgoog.pdf',
            ],
            [
                'title' => 'Critique of Pure Reason',
                'author' => 'Immanuel Kant',
                'category' => 'Philosophy',
                'description' => 'Kant\'s foundational work in modern philosophy.',
                'pages' => 856,
                'price' => 8.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140447477-L.jpg',
                'file' => 'https://archive.org/download/immanuelkantscri00kantuoft/immanuelkantscri00kantuoft.pdf',
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
                'file' => 'https://archive.org/download/leavesofgrass00inwhit/leavesofgrass00inwhit.pdf',
            ],
            [
                'title' => 'The Canterbury Tales',
                'author' => 'Geoffrey Chaucer',
                'category' => 'Poetry',
                'description' => 'A collection of stories told by pilgrims traveling to Canterbury.',
                'pages' => 504,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140424386-L.jpg',
                'file' => 'https://archive.org/download/canterburytales00tyrwgoog/canterburytales00tyrwgoog.pdf',
            ],
            [
                'title' => 'The Divine Comedy',
                'author' => 'Dante Alighieri',
                'category' => 'Poetry',
                'description' => 'An epic poem describing the journey through Hell, Purgatory, and Heaven.',
                'pages' => 798,
                'price' => 5.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140448955-L.jpg',
                'file' => 'https://archive.org/download/divinecomedy03aliggoog/divinecomedy03aliggoog.pdf',
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
                'file' => 'https://archive.org/download/tragedyofromeoan00shakuoft/tragedyofromeoan00shakuoft.pdf',
            ],
            [
                'title' => 'Hamlet',
                'author' => 'William Shakespeare',
                'category' => 'Drama',
                'description' => 'The Prince of Denmark seeks revenge for his father\'s murder.',
                'pages' => 144,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780743477123-L.jpg',
                'file' => 'https://archive.org/download/tragedyhamletpr00shakgoog/tragedyhamletpr00shakgoog.pdf',
            ],
            [
                'title' => 'A Midsummer Night\'s Dream',
                'author' => 'William Shakespeare',
                'category' => 'Drama',
                'description' => 'A comedy about love, magic, and fairies.',
                'pages' => 112,
                'price' => 2.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780743477543-L.jpg',
                'file' => 'https://archive.org/download/a-midsummer-night-s-dream_202505/A_Midsummer_Night___s_Dream_text.pdf',
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
                'file' => 'https://www.planetebook.com/free-ebooks/aesops-fables.pdf',
            ],
            [
                'title' => "Grimm's Fairy Tales",
                'author' => 'Brothers Grimm',
                'category' => 'Children',
                'description' => 'Classic fairy tales including Cinderella, Snow White, and more.',
                'pages' => 512,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140449846-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/grimms-fairy-tales.pdf',
            ],
            [
                'title' => 'The Tale of Peter Rabbit',
                'author' => 'Beatrix Potter',
                'category' => 'Children',
                'description' => 'The mischievous rabbit ventures into Mr. McGregor\'s garden.',
                'pages' => 72,
                'price' => 1.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780723247708-L.jpg',
                'file' => 'https://archive.org/download/peter-rabbit_20220822/Peter%20Rabbit.pdf',
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
                'file' => 'https://www.planetebook.com/free-ebooks/treasure-island.pdf',
            ],
            [
                'title' => 'The Jungle Book',
                'author' => 'Rudyard Kipling',
                'category' => 'Young Adult',
                'description' => 'Stories of Mowgli, a boy raised by wolves in the Indian jungle.',
                'pages' => 277,
                'price' => 0,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780141325295-L.jpg',
                'file' => 'https://www.planetebook.com/free-ebooks/the-jungle-book.pdf',
            ],
            [
                'title' => 'The Swiss Family Robinson',
                'author' => 'Johann David Wyss',
                'category' => 'Young Adult',
                'description' => 'A family is shipwrecked and must survive on a deserted island.',
                'pages' => 352,
                'price' => 4.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140621341-L.jpg',
                'file' => 'https://archive.org/download/swissfamilyrobi03wyssgoog/swissfamilyrobi03wyssgoog.pdf',
            ],
            [
                'title' => 'The Call of the Wild',
                'author' => 'Jack London',
                'category' => 'Young Adult',
                'description' => 'A domesticated dog becomes wild in the Alaskan wilderness.',
                'pages' => 128,
                'price' => 2.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780141321059-L.jpg',
                'file' => 'https://archive.org/download/callwild01londgoog/callwild01londgoog.pdf',
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
                'file' => 'https://archive.org/download/walden03thorgoog/walden03thorgoog.pdf',
            ],
            [
                'title' => 'On Liberty',
                'author' => 'John Stuart Mill',
                'category' => 'Non-Fiction',
                'description' => 'A classic work on the nature and limits of political freedom.',
                'pages' => 128,
                'price' => 3.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140432077-L.jpg',
                'file' => 'https://archive.org/download/onliberty00millgoog/onliberty00millgoog.pdf',
            ],
            [
                'title' => 'The Communist Manifesto',
                'author' => 'Karl Marx and Friedrich Engels',
                'category' => 'Non-Fiction',
                'description' => 'The foundational text of communist political theory.',
                'pages' => 68,
                'price' => 1.99,
                'cover' => 'https://covers.openlibrary.org/b/isbn/9780140447576-L.jpg',
                'file' => 'https://www.marxists.org/archive/marx/works/download/pdf/Manifesto.pdf',
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

        echo "Seeded {$freeCount} free books and {$paidCount} paid books (Total: ".count($books).")\n";
    }
}
