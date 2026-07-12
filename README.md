<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Courier Management API

RESTful API untuk Master Data Kurir (Courier) menggunakan Laravel 13.

## Tech Stack

- **Framework:** Laravel 13
- **Language:** PHP 8.3
- **Database:** MySQL (default), SQLite (testing)
- **Testing:** Pest

## Installation

```bash
# 1. Install dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Create database (MySQL)
CREATE DATABASE courier_app;

# 5. Run migrations & Seeder
php artisan migrate
php artisan db:seed --class=CourierSeeder

# 6. Install npm dependencies
npm install

# 7. Build assets
npm run build
```

## Development

```bash
# Run all services (Laravel server + Queue worker + Vite)
composer run dev

# Run tests
composer test
```

## API Routes

Base URL: `/api/v1/couriers`

| Method | Endpoint | Description | Status Codes |
|--------|----------|-------------|--------------|
| `GET` | `/api/v1/couriers` | List couriers (paginated) | 200 |
| `GET` | `/api/v1/couriers/{id}` | Get courier detail | 200, 404 |
| `POST` | `/api/v1/couriers` | Create new courier | 201, 422 |
| `PUT/PATCH` | `/api/v1/couriers/{id}` | Update courier | 200, 404, 422 |
| `DELETE` | `/api/v1/couriers/{id}` | Delete courier | 204, 404 |

## API Examples

### 1. List Couriers (Index)

**Default (sorted by name A-Z)**
```bash
GET /api/v1/couriers
```

**Response (200 OK)**
```json
{
    "data": [
        {
            "id": 2,
            "name": "Budi Santoso",
            "email": "budi.santoso@courier.id",
            "phone": "081234567891",
            "level": 2,
            "created_at": "2026-07-12T10:00:00+00:00",
            "updated_at": "2026-07-12T10:00:00+00:00"
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/v1/couriers?page=1",
        "last": "http://localhost:8000/api/v1/couriers?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "per_page": 15,
        "to": 10,
        "total": 10
    }
}
```

---

### 2. List with Search

**Search single keyword**
```bash
GET /api/v1/couriers?search=budi
```

**Search multiple keywords (AND logic)**
```bash
GET /api/v1/couriers?search=budi+agung
```
> Matches names containing BOTH "budi" AND "agung" (e.g., "Budiono Hadi Agung")

**Response**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Budiono Hadi Agung",
            "email": "ahmad.rizki@courier.id",
            "phone": "081234567890",
            "level": 3,
            "created_at": "2026-07-12T10:00:00+00:00",
            "updated_at": "2026-07-12T10:00:00+00:00"
        }
    ],
    "meta": { "total": 1, "current_page": 1 }
}
```

---

### 3. List with Level Filter

**Filter by single level**
```bash
GET /api/v1/couriers?level=3
```

**Filter by multiple levels**
```bash
GET /api/v1/couriers?level=1,2,3
```

**Response**
```json
{
    "data": [
        { "id": 1, "name": "Ahmad Rizki Pratama", "level": 3 },
        { "id": 2, "name": "Budi Santoso", "level": 2 },
        { "id": 5, "name": "Joko Pramono", "level": 1 }
    ],
    "meta": { "total": 3, "current_page": 1 }
}
```

---

### 4. List with Sort

**Sort by name A-Z (default)**
```bash
GET /api/v1/couriers
```

**Sort by newest first**
```bash
GET /api/v1/couriers?sort=latest
```

**Response**
```json
{
    "data": [
        { "id": 10, "name": "Kartika Sari", "created_at": "2026-07-12T12:00:00+00:00" },
        { "id": 9, "name": "Joko Pramono", "created_at": "2026-07-12T11:30:00+00:00" }
    ],
    "meta": { "total": 10 }
}
```

---

### 5. List with Match

**Match Search**
```bash
GET /api/v1/couriers?search=budi+agung
```

---

### 6. Get Courier Detail (Show)

```bash
GET /api/v1/couriers/1
```

**Response (200 OK)**
```json
{
    "data": {
        "id": 1,
        "name": "Ahmad Rizki Pratama",
        "email": "ahmad.rizki@courier.id",
        "phone": "081234567890",
        "level": 3,
        "created_at": "2026-07-12T10:00:00+00:00",
        "updated_at": "2026-07-12T10:00:00+00:00"
    }
}
```

**Response (404 Not Found)**
```bash
GET /api/v1/couriers/999
```
```json
{
    "message": "No query results for model [App\\Models\\Courier] 999."
}
```

---

### 7. Create Courier (Store)

```bash
POST /api/v1/couriers
Content-Type: application/json

{
    "name": "Budi Hadi Agung",
    "email": "budi.hadi@courier.id",
    "phone": "081234567891",
    "level": 3
}
```

**Response (201 Created)**
```json
{
    "data": {
        "id": 11,
        "name": "Budi Hadi Agung",
        "email": "budi.hadi@courier.id",
        "phone": "081234567891",
        "level": 3,
        "created_at": "2026-07-12T12:00:00+00:00",
        "updated_at": "2026-07-12T12:00:00+00:00"
    }
}
```

**Response (422 Validation Error)**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."],
        "level": ["The selected level is invalid."]
    }
}
```

---

### 8. Update Courier

```bash
PUT /api/v1/couriers/1
Content-Type: application/json

{
    "name": "Ahmad Rizki Pratama Jr.",
    "email": "ahmad.rizki.jr@courier.id",
    "phone": "081234567899",
    "level": 4
}
```

**Response (200 OK)**
```json
{
    "data": {
        "id": 1,
        "name": "Ahmad Rizki Pratama Jr.",
        "email": "ahmad.rizki.jr@courier.id",
        "phone": "081234567899",
        "level": 4,
        "created_at": "2026-07-12T10:00:00+00:00",
        "updated_at": "2026-07-12T12:30:00+00:00"
    }
}
```

**Response (404 Not Found)**
```bash
PUT /api/v1/couriers/999
```
```json
{
    "message": "No query results for model [App\\Models\\Courier] 999."
}
```

---

### 9. Delete Courier (Destroy)

```bash
DELETE /api/v1/couriers/1
```

**Response (204 No Content)**
```
(empty body)
```

**Response (404 Not Found)**
```json
{
    "message": "No query results for model [App\\Models\\Courier] 999."
}
```

---

### 10. Validation Rules

| Field | Rules |
|-------|-------|
| `name` | required, string, max:255 |
| `email` | required, email, max:255, unique |
| `phone` | required, string, max:15 |
| `level` | required, integer, in:1,2,3,4,5 |

## Feature Tests

All 21 tests covering:

| Test | Description |
|------|-------------|
| Index | Returns 200, paginated structure, default sorting (name ASC) |
| Index Sort | Override sorting with `?sort=latest` |
| Index Search | Single and multi-word fuzzy search |
| Index Filter | Filter by level (comma-separated) |
| Show | Returns courier data or 404 |
| Store | 201 on success, 422 on validation failure |
| Update | 200 on success, 404 for non-existent |
| Destroy | 204 on success, 404 for non-existent |

## Architecture

```
Controller (Thin) → Service (Business Logic) → Model (Data Access)
```

- **Thin Controller:** Only handles request/response
- **Service Layer:** Query building, search, filter logic
- **Local Scopes:** Eloquent scopes for reusable query logic
- **Form Requests:** Separated validation logic
- **API Resources:** JSON transformation

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
