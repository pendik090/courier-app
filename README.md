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
- **Build Tool:** Vite + Tailwind CSS 4

## Installation

```bash
# 1. Install dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Generate application key
php artisan key:generate

```
## CREATE DATABASE
courier_app


```bash
# 4. Run migrations & Seeder
php artisan migrate
php artisan db:seed --class=CourierSeeder

# 5. Install npm dependencies
npm install

# 6. Build assets
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

### Query Parameters (Index)

| Param | Example | Description |
|-------|---------|-------------|
| `sort` | `?sort=latest` | Sort by created_at DESC (default: name ASC) |
| `search` | `?search=budi+agung` | search on name |
| `level` | `?level=1,2,3` | Filter by levels (comma-separated) |
| `page` | `?page=2` | Pagination cursor |

### Request/Response Examples

**Create Courier**
```bash
POST /api/v1/couriers
Content-Type: application/json

{
    "name": "Budi Hadi Agung",
    "email": "budiagung@gmail.com",
    "phone": "081234567891",
    "level": 2
}
```

**Response (201 Created)**
```json
{
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "081234567890",
        "level": 3,
        "created_at": "2026-07-12T20:00:00+00:00",
        "updated_at": "2026-07-12T20:00:00+00:00"
    }
}
```

**List with Search & Filter**
```bash
GET /api/v1/couriers?search=budi&level=2,3&sort=latest
```

## Feature Tests

All tests covering:

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
