# 📚 Book Reader API - Problem Routes & Fixes

## Summary of Issues

| Error Type | Count | Description |
|------------|-------|-------------|
| 500 Internal Server Error | 1 | Registration failure |
| 401 Unauthorized | 40 | Token invalidated after Logout |

**Pass Rate:** 8/52 requests (15.4%)

---

## 🔴 Issue 1: Registration Failure (500 Error)

### Affected Route
| Method | Endpoint | Status |
|--------|----------|--------|
| POST | `/api/auth/register` | 500 Internal Server Error |

### Error Message
```json
{"message": "Registration failed: The email has already been taken."}
```

### Fix
Add this **pre-request script** to use a dynamic email:
```javascript
const randomEmail = `user_${Date.now()}@example.com`;
pm.collectionVariables.set("test_email", randomEmail);
```
Then use `{{test_email}}` in the request body.

---

## 🔴 Issue 2: Authentication Failures (401 Unauthorized)

### Root Cause
The **Logout** request runs before other authenticated requests, invalidating the token.

### Affected Routes (40 requests)

#### Profile
| Method | Endpoint |
|--------|----------|
| GET | `/api/profile` |
| PUT | `/api/profile` |
| POST | `/api/profile/avatar` |
| DELETE | `/api/profile/avatar` |

#### Preferences
| Method | Endpoint |
|--------|----------|
| GET | `/api/preferences` |
| PUT | `/api/preferences` |

#### My Books/Submissions
| Method | Endpoint |
|--------|----------|
| GET | `/api/my-books` |
| POST | `/api/books` |
| DELETE | `/api/my-books/2` |

#### Cart
| Method | Endpoint |
|--------|----------|
| GET | `/api/cart` |
| POST | `/api/cart/books/2` |
| DELETE | `/api/cart/books/2` |
| POST | `/api/cart/checkout` |

#### Orders
| Method | Endpoint |
|--------|----------|
| GET | `/api/orders` |
| GET | `/api/orders/` |

#### Library
| Method | Endpoint |
|--------|----------|
| GET | `/api/library` |
| GET | `/api/library/2/download` |

#### Favorites
| Method | Endpoint |
|--------|----------|
| GET | `/api/favorites` |
| POST | `/api/library/2/favorite` |
| DELETE | `/api/library/2/favorite` |

#### Collections
| Method | Endpoint |
|--------|----------|
| GET | `/api/collections` |
| GET | `/api/collections/285081/books` |
| POST | `/api/collections/285081/books` |
| DELETE | `/api/collections/285081/books/2` |

#### Progress
| Method | Endpoint |
|--------|----------|
| GET | `/api/progress` |
| GET | `/api/progress/2` |
| PUT | `/api/progress/2` |

#### Reviews
| Method | Endpoint |
|--------|----------|
| POST | `/api/books/2/reviews` |

#### Admin Endpoints
| Method | Endpoint |
|--------|----------|
| GET | `/api/admin/stats` |
| GET | `/api/admin/books` |
| GET | `/api/admin/books/2` |
| PUT | `/api/admin/books/2` |
| PUT | `/api/admin/books/2/approve` |
| PUT | `/api/admin/books/2/reject` |
| DELETE | `/api/admin/books/2` |
| GET | `/api/admin/categories` |
| POST | `/api/admin/categories` |
| PUT | `/api/admin/categories/7` |
| DELETE | `/api/admin/categories/7` |
| GET | `/api/admin/users` |
| GET | `/api/admin/users/295749` |

### Fix

**1. Reorganize collection structure:**
```
📁 1. Public Endpoints (no auth)
   ├── List Approved Books
   ├── Get Book Details
   ├── Get Book Reviews
   └── List Categories

📁 2. User Auth
   ├── Register
   ├── Login ← saves token
   └── Get Current User

📁 3. User Endpoints (authenticated)
   ├── Profile/
   ├── Preferences/
   ├── Cart/
   ├── Orders/
   ├── Library/
   ├── Favorites/
   ├── Collections/
   ├── Progress/
   └── Reviews/

📁 4. User Cleanup
   └── Logout ← MOVE HERE (end of user section)

📁 5. Admin Auth
   └── Admin Login ← saves admin token

📁 6. Admin Endpoints
   ├── Dashboard/
   ├── Books Management/
   ├── Categories Management/
   └── Users Management/
```

**2. Add token-saving scripts:**

In **Login** post-response script:
```javascript
const response = pm.response.json();
if (response.success && response.data.token) {
    pm.collectionVariables.set("user_auth", response.data.token);
}
```

In **Admin Login** post-response script:
```javascript
const response = pm.response.json();
if (response.success && response.data.token) {
    pm.collectionVariables.set("admin_auth", response.data.token);
}
```

---

## ✅ Quick Fix Checklist

- [ ] Move Logout to end of user section
- [ ] Move Admin Login before admin endpoints
- [ ] Add dynamic email to Register request
- [ ] Add token-saving script to Login
- [ ] Add token-saving script to Admin Login

---

## Environment Variables Required

| Variable | Description | Set By |
|----------|-------------|--------|
| `base_url` | `https://book-reader-store-backend.onrender.com` | Manual |
| `user_auth` | User authentication token | Login script |
| `admin_auth` | Admin authentication token | Admin Login script |
| `test_email` | Dynamic email for registration | Register pre-request script |
