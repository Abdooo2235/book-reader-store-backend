# Book Reader – Software Requirements Specification (SRS)

## 1. Project Overview

**Project Name:** Book Reader – Free Book Store & Reader Platform  
**Version:** 1.0  
**Date:** January 2026

### 1.1 Purpose

A mobile application platform for downloading, reading, and managing free books. Users can discover books, add them to their library, track reading progress, and leave reviews. Administrators can manage book submissions and categories.

### 1.2 Technology Stack

| Layer              | Technology                                                |
| ------------------ | --------------------------------------------------------- |
| **Mobile App**     | Flutter                                                   |
| **Backend API**    | Laravel 12                                                |
| **Database**       | MySQL 9.4                                                 |
| **Authentication** | Laravel Sanctum (Token-based)                             |
| **File Storage**   | Local (storage/app/books/)                                |
| **Packages**       | spatie/laravel-query-builder, spatie/laravel-medialibrary |

---

## 2. User Roles

| Role      | Description          | Permissions                                                                    |
| --------- | -------------------- | ------------------------------------------------------------------------------ |
| **User**  | Regular app user     | Register, login, browse books, download, read, review, manage collections      |
| **Admin** | System administrator | All user permissions + approve/reject books, manage categories, view all users |

> **Note:** "Author" is an attribute on books, not a separate user role.

---

## 3. Functional Requirements

### 3.1 Authentication (FR-AUTH)

| ID         | Requirement                                                                      |
| ---------- | -------------------------------------------------------------------------------- |
| FR-AUTH-01 | Users can register with name, email, and password                                |
| FR-AUTH-02 | Users can login with email and password                                          |
| FR-AUTH-03 | Users can logout (invalidate token)                                              |
| FR-AUTH-04 | System auto-creates cart, 3 default collections, and preferences on registration |
| FR-AUTH-05 | System returns JWT-like bearer token on login/register                           |

### 3.2 User Profile (FR-PROFILE)

| ID            | Requirement                                           |
| ------------- | ----------------------------------------------------- |
| FR-PROFILE-01 | Users can view their profile with reading stats       |
| FR-PROFILE-02 | Users can update their name                           |
| FR-PROFILE-03 | Users can upload/delete avatar image                  |
| FR-PROFILE-04 | Reading stats are calculated dynamically (not stored) |

**Reading Stats (Calculated):**

-   Total books downloaded
-   Books currently reading (progress 0-100%)
-   Books completed (progress = 100%)
-   Reviews written

### 3.3 Books (FR-BOOK)

| ID         | Requirement                                                        |
| ---------- | ------------------------------------------------------------------ |
| FR-BOOK-01 | Users can browse approved books with filtering and sorting         |
| FR-BOOK-02 | Users can filter by: category, title, author                       |
| FR-BOOK-03 | Users can sort by: title, release_date, average_rating, created_at |
| FR-BOOK-04 | Users can view book details with reviews                           |
| FR-BOOK-05 | Users can submit new books (pending approval)                      |
| FR-BOOK-06 | Users can delete their pending book submissions                    |
| FR-BOOK-07 | Supported file types: PDF, EPUB                                    |
| FR-BOOK-08 | Cover images support both upload and external URL                  |

**Book Attributes:**

-   title, author, description, release_date
-   category_id, file_type (pdf/epub)
-   cover_image, cover_type (upload/url)
-   number_of_pages, average_rating
-   status (pending/approved/rejected), rejection_reason
-   created_by (user who submitted)

### 3.4 Categories (FR-CAT)

| ID        | Requirement                                     |
| --------- | ----------------------------------------------- |
| FR-CAT-01 | Users can view all categories with book count   |
| FR-CAT-02 | Admin can create, update, delete categories     |
| FR-CAT-03 | Categories cannot be deleted if they have books |

**Default Categories (15):**
Fiction, Non-Fiction, Science Fiction, Fantasy, Mystery, Romance, Biography, Self-Help, History, Technology, Philosophy, Poetry, Drama, Children, Young Adult

### 3.5 Cart & Orders (FR-CART)

| ID         | Requirement                                           |
| ---------- | ----------------------------------------------------- |
| FR-CART-01 | Each user has one cart (auto-created on registration) |
| FR-CART-02 | Users can add approved books to cart                  |
| FR-CART-03 | Users can remove books from cart                      |
| FR-CART-04 | Users can checkout cart (creates order)               |
| FR-CART-05 | Cart is cleared after checkout                        |
| FR-CART-06 | Cannot add already-ordered books to cart              |
| FR-CART-07 | Users can view order history                          |

> **Note:** All books are FREE. Cart/Order is for tracking downloads, not payments.

### 3.6 Library (FR-LIB)

| ID        | Requirement                                  |
| --------- | -------------------------------------------- |
| FR-LIB-01 | Users can view their library (ordered books) |
| FR-LIB-02 | Users can download book files from library   |
| FR-LIB-03 | Download requires valid order for the book   |
| FR-LIB-04 | Library shows reading progress for each book |

### 3.7 Collections (FR-COL)

| ID        | Requirement                                                             |
| --------- | ----------------------------------------------------------------------- |
| FR-COL-01 | Users have 3 default collections: "Reading", "Already Read", "Planning" |
| FR-COL-02 | Users can add books to any collection                                   |
| FR-COL-03 | Users can remove books from collections                                 |
| FR-COL-04 | Only ordered books can be added to collections                          |
| FR-COL-05 | Collections show book count                                             |

### 3.8 Reading Progress (FR-PROG)

| ID         | Requirement                                         |
| ---------- | --------------------------------------------------- |
| FR-PROG-01 | Users can view progress for all books               |
| FR-PROG-02 | Users can update progress by setting last_page      |
| FR-PROG-03 | System calculates progress_percentage automatically |
| FR-PROG-04 | Only ordered books can have progress tracked        |

### 3.9 Reviews & Ratings (FR-REV)

| ID        | Requirement                                             |
| --------- | ------------------------------------------------------- |
| FR-REV-01 | Users can view reviews for any book                     |
| FR-REV-02 | Users can create/update their review (1-5 stars + text) |
| FR-REV-03 | Users can delete their own review                       |
| FR-REV-04 | Only users who ordered the book can review              |
| FR-REV-05 | Book average_rating auto-updates on review changes      |
| FR-REV-06 | One review per user per book                            |

### 3.10 User Preferences (FR-PREF)

| ID         | Requirement                                  |
| ---------- | -------------------------------------------- |
| FR-PREF-01 | Users can view their preferences             |
| FR-PREF-02 | Users can update theme (light/dark)          |
| FR-PREF-03 | Users can update font_size (12-32)           |
| FR-PREF-04 | Preferences are auto-created on registration |

### 3.11 Admin Dashboard (FR-ADMIN)

| ID          | Requirement                                  |
| ----------- | -------------------------------------------- |
| FR-ADMIN-01 | Admin can view dashboard stats               |
| FR-ADMIN-02 | Admin can view all books (including pending) |
| FR-ADMIN-03 | Admin can approve pending books              |
| FR-ADMIN-04 | Admin can reject books with reason           |
| FR-ADMIN-05 | Admin can update/delete any book             |
| FR-ADMIN-06 | Admin can CRUD categories                    |
| FR-ADMIN-07 | Admin can view all users                     |

---

## 4. Entities & Attributes

### 4.1 User

| Attribute         | Type        | Constraints                      | Description             |
| ----------------- | ----------- | -------------------------------- | ----------------------- |
| id                | bigint      | PK, auto-increment               | Unique identifier       |
| name              | string(255) | required                         | User's display name     |
| email             | string(255) | required, unique                 | Login email             |
| password          | string      | required, hashed                 | Bcrypt hashed password  |
| avatar_path       | string      | nullable                         | Path to avatar image    |
| role              | enum        | 'user', 'admin', default: 'user' | User role               |
| email_verified_at | timestamp   | nullable                         | Email verification date |
| created_at        | timestamp   | auto                             | Registration date       |
| updated_at        | timestamp   | auto                             | Last update date        |
| deleted_at        | timestamp   | nullable                         | Soft delete date        |

**Relationships:**

-   Has one `Cart`
-   Has many `Order`
-   Has many `Collection`
-   Has many `ReadingProgress`
-   Has many `Review`
-   Has one `UserPreference`
-   Has many `Book` (as creator)

---

### 4.2 Book

| Attribute        | Type         | Constraints                        | Description               |
| ---------------- | ------------ | ---------------------------------- | ------------------------- |
| id               | bigint       | PK, auto-increment                 | Unique identifier         |
| title            | string(255)  | required                           | Book title                |
| author           | string(255)  | required                           | Author name               |
| release_date     | date         | nullable                           | Publication date          |
| description      | text         | nullable                           | Book description          |
| category_id      | bigint       | FK → categories                    | Book category             |
| cover_image      | string       | nullable                           | Cover image path or URL   |
| cover_type       | enum         | 'upload', 'url', default: 'upload' | Cover source type         |
| file_type        | enum         | 'pdf', 'epub', default: 'pdf'      | Book file format          |
| file_url         | string       | nullable                           | Path to book file         |
| number_of_pages  | integer      | default: 0                         | Total pages in book       |
| status           | enum         | 'pending', 'approved', 'rejected'  | Approval status           |
| rejection_reason | string       | nullable                           | Admin rejection reason    |
| average_rating   | decimal(3,2) | default: 0.00                      | Calculated average rating |
| created_by       | bigint       | FK → users                         | Submitting user           |
| created_at       | timestamp    | auto                               | Submission date           |
| updated_at       | timestamp    | auto                               | Last update date          |
| deleted_at       | timestamp    | nullable                           | Soft delete date          |

**Relationships:**

-   Belongs to `Category`
-   Belongs to `User` (creator)
-   Has many `Review`
-   Has many `ReadingProgress`

---

### 4.3 Category

| Attribute  | Type        | Constraints        | Description       |
| ---------- | ----------- | ------------------ | ----------------- |
| id         | bigint      | PK, auto-increment | Unique identifier |
| name       | string(100) | required, unique   | Category name     |
| created_at | timestamp   | auto               | Creation date     |
| updated_at | timestamp   | auto               | Last update date  |

**Relationships:**

-   Has many `Book`

---

### 4.4 Cart

| Attribute  | Type      | Constraints        | Description       |
| ---------- | --------- | ------------------ | ----------------- |
| id         | bigint    | PK, auto-increment | Unique identifier |
| user_id    | bigint    | FK → users, unique | Cart owner        |
| created_at | timestamp | auto               | Creation date     |
| updated_at | timestamp | auto               | Last update date  |

**Relationships:**

-   Belongs to `User`
-   Has many `CartItem`
-   Belongs to many `Book` (through cart_items)

---

### 4.5 CartItem

| Attribute  | Type      | Constraints        | Description       |
| ---------- | --------- | ------------------ | ----------------- |
| id         | bigint    | PK, auto-increment | Unique identifier |
| cart_id    | bigint    | FK → carts         | Parent cart       |
| book_id    | bigint    | FK → books         | Book in cart      |
| created_at | timestamp | auto               | Added date        |
| updated_at | timestamp | auto               | Last update date  |

**Constraints:** Unique combination of (cart_id, book_id)

---

### 4.6 Order

| Attribute   | Type      | Constraints        | Description       |
| ----------- | --------- | ------------------ | ----------------- |
| id          | bigint    | PK, auto-increment | Unique identifier |
| user_id     | bigint    | FK → users         | Order owner       |
| total_items | integer   | default: 0         | Number of books   |
| created_at  | timestamp | auto               | Order date        |
| updated_at  | timestamp | auto               | Last update date  |

**Relationships:**

-   Belongs to `User`
-   Has many `OrderItem`
-   Belongs to many `Book` (through order_items)

---

### 4.7 OrderItem

| Attribute  | Type      | Constraints        | Description       |
| ---------- | --------- | ------------------ | ----------------- |
| id         | bigint    | PK, auto-increment | Unique identifier |
| order_id   | bigint    | FK → orders        | Parent order      |
| book_id    | bigint    | FK → books         | Ordered book      |
| created_at | timestamp | auto               | Creation date     |
| updated_at | timestamp | auto               | Last update date  |

---

### 4.8 Collection

| Attribute  | Type        | Constraints        | Description               |
| ---------- | ----------- | ------------------ | ------------------------- |
| id         | bigint      | PK, auto-increment | Unique identifier         |
| user_id    | bigint      | FK → users         | Collection owner          |
| name       | string(100) | required           | Collection name           |
| is_default | boolean     | default: false     | System-created collection |
| created_at | timestamp   | auto               | Creation date             |
| updated_at | timestamp   | auto               | Last update date          |

**Default Collections:** "Reading", "Already Read", "Planning"

**Relationships:**

-   Belongs to `User`
-   Belongs to many `Book` (through collection_book)

---

### 4.9 ReadingProgress

| Attribute           | Type         | Constraints        | Description               |
| ------------------- | ------------ | ------------------ | ------------------------- |
| id                  | bigint       | PK, auto-increment | Unique identifier         |
| user_id             | bigint       | FK → users         | Reader                    |
| book_id             | bigint       | FK → books         | Book being read           |
| progress_percentage | decimal(5,2) | default: 0.00      | Reading progress (0-100%) |
| last_page           | integer      | default: 0         | Last page read            |
| created_at          | timestamp    | auto               | Start date                |
| updated_at          | timestamp    | auto               | Last reading date         |

**Constraints:** Unique combination of (user_id, book_id)

---

### 4.10 Review

| Attribute   | Type      | Constraints        | Description       |
| ----------- | --------- | ------------------ | ----------------- |
| id          | bigint    | PK, auto-increment | Unique identifier |
| user_id     | bigint    | FK → users         | Reviewer          |
| book_id     | bigint    | FK → books         | Reviewed book     |
| rating      | tinyint   | required, 1-5      | Star rating       |
| review_text | text      | nullable           | Review content    |
| created_at  | timestamp | auto               | Review date       |
| updated_at  | timestamp | auto               | Last update date  |

**Constraints:** Unique combination of (user_id, book_id) - one review per user per book

---

### 4.11 UserPreference

| Attribute  | Type      | Constraints                       | Description       |
| ---------- | --------- | --------------------------------- | ----------------- |
| id         | bigint    | PK, auto-increment                | Unique identifier |
| user_id    | bigint    | FK → users, unique                | Preference owner  |
| theme      | enum      | 'light', 'dark', default: 'light' | App theme         |
| font_size  | integer   | default: 16, range: 12-32         | Reader font size  |
| created_at | timestamp | auto                              | Creation date     |
| updated_at | timestamp | auto                              | Last update date  |

---

## 5. App Navigation & Screens

### 5.1 Navigation Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                         SPLASH SCREEN                            │
│                              ↓                                   │
│              ┌──────── Auth Check ────────┐                     │
│              ↓                            ↓                     │
│       [Not Logged In]              [Logged In]                  │
│              ↓                            ↓                     │
│       ONBOARDING/LOGIN             MAIN APP (Tabs)              │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                    MAIN APP (Bottom Navigation)                  │
├─────────┬─────────┬─────────┬─────────┬─────────────────────────┤
│  Home   │ Library │  Cart   │ Profile │      (5 Tabs)           │
│   📚    │   📖    │   🛒    │   👤    │                         │
└─────────┴─────────┴─────────┴─────────┴─────────────────────────┘
```

---

### 5.2 Screen List

#### Authentication Screens

| Screen         | Route         | Description                        |
| -------------- | ------------- | ---------------------------------- |
| **Splash**     | `/`           | App loading, auth check            |
| **Onboarding** | `/onboarding` | First-time user intro (optional)   |
| **Login**      | `/login`      | Email/password login form          |
| **Register**   | `/register`   | Name, email, password registration |

---

#### Main Screens (Bottom Navigation)

| Tab | Screen      | Route      | Description                        |
| --- | ----------- | ---------- | ---------------------------------- |
| 1   | **Home**    | `/home`    | Book discovery, categories, search |
| 2   | **Library** | `/library` | User's downloaded books            |
| 3   | **Cart**    | `/cart`    | Books to order                     |
| 4   | **Profile** | `/profile` | User info, settings, stats         |

---

#### Book Screens

| Screen             | Route                   | Description                     |
| ------------------ | ----------------------- | ------------------------------- |
| **Book List**      | `/books`                | Browse all books with filters   |
| **Book Detail**    | `/books/:id`            | Book info, reviews, add to cart |
| **Book Reader**    | `/reader/:id`           | PDF/EPUB reader with progress   |
| **Category Books** | `/categories/:id/books` | Books in specific category      |
| **Search Results** | `/search`               | Search results page             |

---

#### Library Screens

| Screen                | Route              | Description                     |
| --------------------- | ------------------ | ------------------------------- |
| **My Library**        | `/library`         | All downloaded books            |
| **Collections**       | `/collections`     | Reading, Already Read, Planning |
| **Collection Detail** | `/collections/:id` | Books in collection             |
| **Reading Progress**  | `/progress`        | All books with progress         |

---

#### Cart & Order Screens

| Screen               | Route               | Description        |
| -------------------- | ------------------- | ------------------ |
| **Cart**             | `/cart`             | Current cart items |
| **Checkout Success** | `/checkout/success` | Order confirmation |
| **Order History**    | `/orders`           | Past orders list   |
| **Order Detail**     | `/orders/:id`       | Order items        |

---

#### Profile Screens

| Screen           | Route           | Description              |
| ---------------- | --------------- | ------------------------ |
| **Profile**      | `/profile`      | User info, reading stats |
| **Edit Profile** | `/profile/edit` | Update name, avatar      |
| **My Books**     | `/my-books`     | Submitted books list     |
| **Submit Book**  | `/submit-book`  | Book submission form     |
| **Settings**     | `/settings`     | Theme, font size prefs   |
| **Reviews**      | `/my-reviews`   | User's reviews           |

---

#### Admin Screens (Admin Role Only)

| Screen              | Route                         | Description             |
| ------------------- | ----------------------------- | ----------------------- |
| **Admin Dashboard** | `/admin`                      | Stats overview          |
| **Pending Books**   | `/admin/books?status=pending` | Books awaiting approval |
| **All Books**       | `/admin/books`                | Manage all books        |
| **Book Approval**   | `/admin/books/:id`            | Approve/reject book     |
| **Categories**      | `/admin/categories`           | CRUD categories         |
| **Users**           | `/admin/users`                | View all users          |

---

### 5.3 Screen Wireframe Descriptions

#### Home Screen

```
┌────────────────────────────────┐
│ 🔍 Search books...             │  ← Search Bar
├────────────────────────────────┤
│ Categories                     │
│ [Fiction] [Tech] [Romance] ... │  ← Horizontal scroll
├────────────────────────────────┤
│ 📚 Popular Books               │
│ ┌─────┐ ┌─────┐ ┌─────┐       │
│ │Cover│ │Cover│ │Cover│       │  ← Horizontal cards
│ │Title│ │Title│ │Title│       │
│ │⭐4.5│ │⭐4.2│ │⭐4.8│       │
│ └─────┘ └─────┘ └─────┘       │
├────────────────────────────────┤
│ 🆕 Recently Added              │
│ [ Book Card with details ]     │  ← Vertical list
│ [ Book Card with details ]     │
└────────────────────────────────┘
```

#### Book Detail Screen

```
┌────────────────────────────────┐
│ ← Back              ♥ ⋮        │  ← App bar
├────────────────────────────────┤
│      ┌──────────────┐          │
│      │              │          │
│      │  Book Cover  │          │  ← Cover image
│      │              │          │
│      └──────────────┘          │
│      Title of the Book         │
│      by Author Name            │
│      ⭐ 4.5 (123 reviews)      │
├────────────────────────────────┤
│ [Add to Cart]  [Add to Library]│  ← Action buttons
├────────────────────────────────┤
│ Description                    │
│ Lorem ipsum dolor sit amet...  │
├────────────────────────────────┤
│ Details                        │
│ Pages: 250  │  Format: PDF     │
│ Category: Fiction              │
│ Released: Jan 2026             │
├────────────────────────────────┤
│ Reviews (123)            See All│
│ ┌──────────────────────────┐   │
│ │ User • ⭐⭐⭐⭐⭐          │   │
│ │ Great book! Loved it...   │   │
│ └──────────────────────────┘   │
└────────────────────────────────┘
```

#### Profile Screen

```
┌────────────────────────────────┐
│ Profile                    ⚙️  │  ← Settings icon
├────────────────────────────────┤
│         ┌─────────┐            │
│         │ Avatar  │            │  ← User avatar
│         └─────────┘            │
│         User Name              │
│         user@email.com         │
│         [Edit Profile]         │
├────────────────────────────────┤
│ 📊 Reading Stats               │
│ ┌────────┬────────┬──────────┐ │
│ │   5    │   2    │    3     │ │
│ │Download│Reading │Completed │ │
│ └────────┴────────┴──────────┘ │
├────────────────────────────────┤
│ 📚 My Submissions         (3) →│  ← Navigate to list
│ 📖 My Collections         (3) →│
│ ⭐ My Reviews             (5) →│
│ 📋 Order History         (10) →│
├────────────────────────────────┤
│ [Logout]                       │
└────────────────────────────────┘
```

#### Book Reader Screen

```
┌────────────────────────────────┐
│ ← Book Title            Aa ☰  │  ← Font/Settings
├────────────────────────────────┤
│                                │
│   Lorem ipsum dolor sit amet,  │
│   consectetur adipiscing elit. │
│   Sed do eiusmod tempor        │
│   incididunt ut labore et      │
│   dolore magna aliqua. Ut enim │  ← Book content
│   ad minim veniam, quis        │
│   nostrud exercitation ullamco │
│   laboris nisi ut aliquip ex   │
│   ea commodo consequat.        │
│                                │
├────────────────────────────────┤
│ ◀ ────────●───────────────── ▶ │  ← Page slider
│           Page 50 of 250       │
│           20% complete         │
└────────────────────────────────┘
```

---

### 5.4 Navigation Actions

| From Screen | Action         | To Screen                  |
| ----------- | -------------- | -------------------------- |
| Home        | Tap category   | Category Books             |
| Home        | Tap book card  | Book Detail                |
| Home        | Tap search     | Search                     |
| Book Detail | Add to Cart    | Cart (with snackbar)       |
| Book Detail | View Reviews   | Reviews List               |
| Cart        | Checkout       | Checkout Success → Library |
| Library     | Tap book       | Book Reader                |
| Profile     | Edit Profile   | Edit Profile               |
| Profile     | My Submissions | My Books List              |
| My Books    | Submit New     | Submit Book Form           |

---

## 6. Non-Functional Requirements

### 6.1 Performance

-   API response time < 500ms for standard queries
-   Pagination limit: 15-100 items per page
-   Maximum file upload: 50MB (books), 2MB (images)

### 6.2 Security

-   All passwords hashed (bcrypt)
-   Token-based authentication (Sanctum)
-   Role-based access control
-   Input validation on all endpoints
-   SQL injection prevention (Eloquent ORM)

### 6.3 Scalability

-   Stateless API design
-   Supports horizontal scaling
-   Database indexes on foreign keys

### 6.4 Reliability

-   Soft deletes for users and books
-   Transaction support for order creation
-   Graceful error handling with JSON responses

---

## 7. Database Schema

### 7.1 Entity Relationship Diagram

```
users ──┬── carts (1:1)
        ├── orders (1:N) ── order_items ── books
        ├── collections (1:N) ── collection_book ── books
        ├── reading_progress (1:N) ── books
        ├── reviews (1:N) ── books
        ├── user_preferences (1:1)
        └── books (created_by, 1:N)

categories ── books (1:N)
```

### 7.2 Table Specifications

| Table                | Key Columns                                                    | Notes               |
| -------------------- | -------------------------------------------------------------- | ------------------- |
| **users**            | id, name, email, password, avatar_path, role, deleted_at       | Soft deletes        |
| **categories**       | id, name (unique)                                              | 15 seeded           |
| **books**            | id, title, author, category_id, status, created_by, deleted_at | Soft deletes        |
| **carts**            | id, user_id (unique)                                           | One per user        |
| **cart_items**       | id, cart_id, book_id                                           | Unique pair         |
| **orders**           | id, user_id, total_items                                       | Free orders         |
| **order_items**      | id, order_id, book_id                                          | Order details       |
| **collections**      | id, user_id, name, is_default                                  | 3 defaults per user |
| **collection_book**  | id, collection_id, book_id, added_at                           | Unique pair         |
| **reading_progress** | id, user_id, book_id, progress_percentage, last_page           | Unique pair         |
| **reviews**          | id, user_id, book_id, rating (1-5), review_text                | Unique pair         |
| **user_preferences** | id, user_id (unique), theme, font_size                         | One per user        |

---

## 8. API Endpoints Summary

### Public (No Auth)

| Method | Endpoint                | Description         |
| ------ | ----------------------- | ------------------- |
| POST   | /api/register           | Register new user   |
| POST   | /api/login              | Login user          |
| GET    | /api/books              | List approved books |
| GET    | /api/books/{id}         | Get book details    |
| GET    | /api/categories         | List categories     |
| GET    | /api/books/{id}/reviews | Get book reviews    |

### User (Auth Required)

| Method      | Endpoint                           | Description          |
| ----------- | ---------------------------------- | -------------------- |
| POST        | /api/logout                        | Logout               |
| GET         | /api/user                          | Get current user     |
| GET/PUT     | /api/profile                       | Get/update profile   |
| POST/DELETE | /api/profile/avatar                | Upload/delete avatar |
| POST        | /api/books                         | Submit new book      |
| GET         | /api/my-books                      | My submissions       |
| DELETE      | /api/my-books/{id}                 | Delete pending book  |
| GET         | /api/cart                          | View cart            |
| POST        | /api/cart/books/{id}               | Add to cart          |
| DELETE      | /api/cart/books/{id}               | Remove from cart     |
| POST        | /api/cart/checkout                 | Checkout             |
| GET         | /api/orders                        | Order history        |
| GET         | /api/library                       | My library           |
| GET         | /api/library/{id}/download         | Download book        |
| GET         | /api/collections                   | My collections       |
| GET         | /api/collections/{id}/books        | Collection books     |
| POST        | /api/collections/{id}/books        | Add book             |
| DELETE      | /api/collections/{id}/books/{book} | Remove book          |
| GET         | /api/progress                      | All progress         |
| GET/PUT     | /api/progress/{book}               | Book progress        |
| POST        | /api/books/{id}/reviews            | Create review        |
| PUT/DELETE  | /api/reviews/{id}                  | Update/delete review |
| GET/PUT     | /api/preferences                   | Preferences          |

### Admin (Auth + Admin Role)

| Method | Endpoint                      | Description       |
| ------ | ----------------------------- | ----------------- |
| GET    | /api/admin/stats              | Dashboard stats   |
| GET    | /api/admin/books              | All books         |
| PUT    | /api/admin/books/{id}         | Update book       |
| DELETE | /api/admin/books/{id}         | Delete book       |
| PUT    | /api/admin/books/{id}/approve | Approve           |
| PUT    | /api/admin/books/{id}/reject  | Reject            |
| CRUD   | /api/admin/categories         | Manage categories |
| GET    | /api/admin/users              | List users        |

**Total: 53 API endpoints**

---

## 9. Business Rules

### 9.1 Book Submission Flow

```
User submits book → status: "pending"
                         ↓
             Admin reviews
            ↙          ↘
   Approved           Rejected
status: "approved"   status: "rejected"
       ↓                    ↓
 Visible in store    User sees rejection_reason
```

### 9.2 Auto-Creation on Registration

When a user registers, the system automatically creates:

1. One empty Cart
2. Three Collections: "Reading", "Already Read", "Planning"
3. One UserPreference with defaults (theme: light, font_size: 16)

### 9.3 Order/Library Logic

-   Users "order" free books to add them to their library
-   Only ordered books can be: downloaded, added to collections, have progress tracked, reviewed

---

## 10. Test Credentials

| Role  | Email                | Password |
| ----- | -------------------- | -------- |
| Admin | admin@bookreader.com | password |

---

## 11. Future Enhancements (Out of Scope)

-   Push notifications
-   Book sharing
-   Social features (following users)
-   Book recommendations
-   PDF cover auto-extraction
-   Cloud storage (S3)
-   Email verification
-   Password reset
