# Book Reader – Backend Modification Checklist

## Phase 1: Planning ✅

- [x] Analyze existing book-store project
- [x] Compare current vs required structure
- [x] Create updated requirements document
- [x] Create modification plan
- [x] Review plan with user

## Phase 2: Database Modifications ✅

- [x] Decide: Fresh install
- [x] Modified users table (role enum: user/admin, soft deletes)
- [x] Created books table (status workflow, file fields, soft deletes)
- [x] Created carts table (simplified)
- [x] Created cart_items table
- [x] Created orders table (user_id, total_items)
- [x] Created order_items table
- [x] Created collections table
- [x] Created collection_book pivot table
- [x] Created reading_progress table
- [x] Created reviews table
- [x] Created user_preferences table
- [x] Installed Spatie MediaLibrary (media table)
- [x] Ran migrations successfully

## Phase 3: Model Updates ✅

- [x] Updated User model (relationships, auto-create cart/collections/prefs)
- [x] Updated Book model (MediaLibrary, status scopes, rating calc)
- [x] Updated Cart model (helper methods)
- [x] Updated Order model (createFromCart factory)
- [x] Deleted Author, Customer, PaymentMethod models
- [x] Created Collection model
- [x] Created ReadingProgress model
- [x] Created Review model (rating update trigger)
- [x] Created UserPreference model

## Phase 4: Controller Creation ✅

### Auth

- [x] Updated AuthController (register, login, logout, user)

### User Controllers

- [x] BookController (submit book with MediaLibrary)
- [x] MyBookController (user's submissions, delete pending)
- [x] CartController (add, remove, checkout)
- [x] OrderController (history)
- [x] LibraryController (downloaded books, download file)
- [x] CollectionController (CRUD books in collections)
- [x] ProgressController (update reading progress)
- [x] ReviewController (CRUD reviews)
- [x] PreferenceController (theme, font)

### Admin Controllers

- [x] BookController (all books, approve, reject)
- [x] CategoryController (CRUD)
- [x] DashboardController (stats)
- [x] UserController (list users)

## Phase 5: Routes & Middleware ✅

- [x] Updated AdminMiddleware
- [x] Updated api.php with 44 routes
- [x] Removed old Author/Customer routes and controllers

## Phase 6: Packages & Seeding ✅

- [x] Installed spatie/laravel-query-builder
- [x] Installed spatie/laravel-medialibrary
- [x] Created CategorySeeder (15 categories)
- [x] Created AdminSeeder (admin@bookreader.com)
- [x] Database seeded successfully

## Phase 7: Testing ⏳

- [ ] Test user registration (auto-create cart, collections, prefs)
- [ ] Test book submission (pending status)
- [ ] Test admin approve/reject
- [ ] Test cart → order → library flow
- [ ] Test collections
- [ ] Test reading progress
- [ ] Test reviews
