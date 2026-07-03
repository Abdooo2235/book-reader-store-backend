# Book Reader – Laravel Backend Modification Plan

## 📊 Requirements Analysis

### Current State vs New Requirements

| Component           | Current State                              | New Requirement                            | Action    |
| ------------------- | ------------------------------------------ | ------------------------------------------ | --------- |
| **User Roles**      | user, admin                                | user, admin                                | ✅ Match  |
| **Book Status**     | pending, approved, rejected                | pending, approved, rejected                | ✅ Match  |
| **Navigation**      | Store, Library, Cart, Collections, Profile | Store, Library, Cart, Collections, Profile | ✅ Match  |
| **Collections**     | Reading, Already Read, Planning            | Reading, Already Read, Planning            | ✅ Match  |
| **Favorites**       | ❌ Not implemented                         | Mark books as favorites                    | ⚠️ Add    |
| **Old Models**      | Author, Customer, PaymentMethod            | Not needed                                 | 🗑️ Remove |
| **Old Controllers** | Author/, Customer/                         | Not needed                                 | 🗑️ Remove |

---

## ⚠️ Questions for Clarification

Before proceeding, please confirm the following:

### 1. **Favorites Feature**

The requirements mention "Mark books as favorites" in the Library screen.

**Options:**

- **A)** Create a separate `favorites` table (user_id, book_id)
- **B)** Add a `is_favorite` boolean to the `collection_book` pivot table
- **C)** Create a default "Favorites" collection (4th collection)

**Recommendation:** Option C - Add a 4th default collection called "Favorites"

---

### 2. **Book Submission - Cover Image Source**

The current system supports both file upload and URL for cover images.

**Options:**

- **A)** Keep both options (upload + URL)
- **B)** Only file upload
- **C)** Only external URL

**Current:** Option A (both)

---

### 3. **Book File Storage**

Current: Using Spatie MediaLibrary (local storage)

**Options:**

- **A)** Keep local storage (current)
- **B)** Switch to cloud storage (S3, etc.)

**Recommendation:** Keep A for now, easy to switch later

---

### 4. **Reading Progress - Update Trigger**

The requirement says "Auto-update while reading"

**Options:**

- **A)** Flutter app sends progress every page turn
- **B)** Flutter app sends progress on book close
- **C)** Flutter app sends progress every N pages or on book close

**Recommendation:** Option C - Balance between accuracy and API calls

---

### 5. **React Admin Dashboard**

The requirements mention a React admin dashboard.

**Questions:**

- Will the React dashboard be in a separate repository?
- Should the Laravel backend serve as API-only (current) or also serve the React build?

**Recommendation:** Separate repository, API-only backend

---

## 📝 Modification Plan

### Phase 1: Cleanup (Remove Old Files)

#### Models to Delete:

```
app/Models/Author.php
app/Models/BookRequestAuthor.php
app/Models/Customer.php
app/Models/PaymentMethod.php
```

#### Controller Directories to Delete:

```
app/Http/Controllers/Author/
app/Http/Controllers/Customer/
```

#### Old Migrations to Check:

- Remove any migrations for deleted models

---

### Phase 2: Favorites Feature (If Option C Approved)

#### Modify User Model:

Add 4th default collection "Favorites" in `booted()` method:

```php
foreach (['Reading', 'Already Read', 'Planning', 'Favorites'] as $name) {
    $user->collections()->create(['name' => $name, 'is_default' => true]);
}
```

#### Update Existing Users:

Create a migration/seeder to add "Favorites" collection to existing users:

```php
User::all()->each(function ($user) {
    if (!$user->collections()->where('name', 'Favorites')->exists()) {
        $user->collections()->create(['name' => 'Favorites', 'is_default' => true]);
    }
});
```

#### Add Favorites Endpoints:

```
POST   /api/library/{book}/favorite      - Add to favorites
DELETE /api/library/{book}/favorite      - Remove from favorites
GET    /api/favorites                    - Get all favorites
```

---

### Phase 3: API Endpoint Verification

#### Store (Books) Endpoints ✅

| Endpoint            | Method                               | Current | Required |
| ------------------- | ------------------------------------ | ------- | -------- |
| List approved books | GET /api/books                       | ✅      | ✅       |
| Get book details    | GET /api/books/{id}                  | ✅      | ✅       |
| Filter by category  | GET /api/books?filter[category_id]=1 | ✅      | ✅       |
| View ratings        | Included in book details             | ✅      | ✅       |
| Submit book         | POST /api/books                      | ✅      | ✅       |

#### Library Endpoints ✅

| Endpoint              | Method                          | Current | Required |
| --------------------- | ------------------------------- | ------- | -------- |
| View downloaded books | GET /api/library                | ✅      | ✅       |
| Download book         | GET /api/library/{id}/download  | ✅      | ✅       |
| Reading progress      | GET /api/progress               | ✅      | ✅       |
| Update progress       | PUT /api/progress/{book}        | ✅      | ✅       |
| Mark as favorite      | POST /api/library/{id}/favorite | ❌      | ⚠️ Add   |

#### Cart Endpoints ✅

| Endpoint         | Method                      | Current | Required |
| ---------------- | --------------------------- | ------- | -------- |
| View cart        | GET /api/cart               | ✅      | ✅       |
| Add to cart      | POST /api/cart/books/{id}   | ✅      | ✅       |
| Remove from cart | DELETE /api/cart/books/{id} | ✅      | ✅       |
| Checkout         | POST /api/cart/checkout     | ✅      | ✅       |

#### Collections Endpoints ✅

| Endpoint         | Method                                    | Current | Required |
| ---------------- | ----------------------------------------- | ------- | -------- |
| List collections | GET /api/collections                      | ✅      | ✅       |
| Collection books | GET /api/collections/{id}/books           | ✅      | ✅       |
| Add book         | POST /api/collections/{id}/books          | ✅      | ✅       |
| Remove book      | DELETE /api/collections/{id}/books/{book} | ✅      | ✅       |

#### Profile Endpoints ✅

| Endpoint            | Method                    | Current | Required |
| ------------------- | ------------------------- | ------- | -------- |
| Get profile         | GET /api/profile          | ✅      | ✅       |
| Update profile      | PUT /api/profile          | ✅      | ✅       |
| Get preferences     | GET /api/preferences      | ✅      | ✅       |
| Update preferences  | PUT /api/preferences      | ✅      | ✅       |
| My submitted books  | GET /api/my-books         | ✅      | ✅       |
| Delete pending book | DELETE /api/my-books/{id} | ✅      | ✅       |
| Logout              | POST /api/auth/logout     | ✅      | ✅       |

#### Reviews Endpoints ✅

| Endpoint      | Method                       | Current | Required |
| ------------- | ---------------------------- | ------- | -------- |
| View reviews  | GET /api/books/{id}/reviews  | ✅      | ✅       |
| Add review    | POST /api/books/{id}/reviews | ✅      | ✅       |
| Update review | PUT /api/reviews/{id}        | ✅      | ✅       |
| Delete review | DELETE /api/reviews/{id}     | ✅      | ✅       |

#### Admin Endpoints ✅

| Endpoint        | Method                            | Current | Required |
| --------------- | --------------------------------- | ------- | -------- |
| Dashboard stats | GET /api/admin/stats              | ✅      | ✅       |
| All books       | GET /api/admin/books              | ✅      | ✅       |
| Approve book    | PUT /api/admin/books/{id}/approve | ✅      | ✅       |
| Reject book     | PUT /api/admin/books/{id}/reject  | ✅      | ✅       |
| Update book     | PUT /api/admin/books/{id}         | ✅      | ✅       |
| Delete book     | DELETE /api/admin/books/{id}      | ✅      | ✅       |
| CRUD categories | /api/admin/categories             | ✅      | ✅       |
| List users      | GET /api/admin/users              | ✅      | ✅       |

---

### Phase 4: Entity Verification

| Entity          | Model                  | Migration | Status   |
| --------------- | ---------------------- | --------- | -------- |
| User            | ✅ User.php            | ✅        | Complete |
| Book            | ✅ Book.php            | ✅        | Complete |
| Category        | ✅ Category.php        | ✅        | Complete |
| ReadingProgress | ✅ ReadingProgress.php | ✅        | Complete |
| Review          | ✅ Review.php          | ✅        | Complete |
| Collection      | ✅ Collection.php      | ✅        | Complete |
| CollectionBook  | ✅ (pivot)             | ✅        | Complete |
| UserPreference  | ✅ UserPreference.php  | ✅        | Complete |
| Cart            | ✅ Cart.php            | ✅        | Complete |
| Order           | ✅ Order.php           | ✅        | Complete |
| OrderItem       | ✅ OrderItem.php       | ✅        | Complete |

---

## 📋 Implementation Checklist

### Immediate Actions (High Priority)

- [ ] Delete old model files (Author, BookRequestAuthor, Customer, PaymentMethod)
- [ ] Delete old controller directories (Author/, Customer/)
- [ ] Add "Favorites" as 4th default collection (if approved)
- [ ] Add favorite/unfavorite endpoints

### Verification Actions

- [ ] Run all tests: `php artisan tinker --execute="include 'tests/test_endpoints.php';"`
- [ ] Verify routes: `php artisan route:list`
- [ ] Test all endpoints manually or with Postman

---

## 📊 Summary

| Category           | Items         | Status            |
| ------------------ | ------------- | ----------------- |
| Models Required    | 11            | ✅ 11 implemented |
| Endpoints Required | ~50           | ✅ 53 implemented |
| Missing Features   | 1 (Favorites) | ⚠️ Needs decision |
| Files to Remove    | 8             | 🔄 Pending        |

---

## ✅ Next Steps

1. **Answer the 5 questions above**
2. **Approval to proceed with cleanup and modifications**
3. **Implementation (~1-2 hours)**
4. **Testing and verification**
