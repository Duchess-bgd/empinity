# Impinity

Impinity is a Laravel 13 API-only backend for a simple product catalog.

The API currently supports:

- listing categories
- listing products
- filtering products by search term and category
- creating products
- updating products
- deleting products

Full API details are available in [API_DOCUMENTATION.md](C:/Users/gorda/Documents/herd/impinity/API_DOCUMENTATION.md:1).

## Requirements

- PHP `^8.3`
- Composer
- A database supported by Laravel

## Quick Start

### 1. Install dependencies

```bash
composer install
```

### 2. Create your environment file

```bash
copy .env.example .env
```

### 3. Generate an app key

```bash
php artisan key:generate
```

### 4. Configure your database

Update `.env` with your database connection settings.

Example SQLite configuration:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### 5. Run migrations and seed demo data

```bash
php artisan migrate --seed
```

### 6. Start the server

```bash
php artisan serve
```

The API will then be available at:

```text
http://localhost:8000
```

If you use Herd, your local URL may instead be:

```text
http://impinity.test
```

## Main Endpoints

- `GET /api/categories`
- `GET /api/products`
- `POST /api/products`
- `PUT /api/products/{id}`
- `DELETE /api/products/{id}`

Examples and payload details are in [API_DOCUMENTATION.md](C:/Users/gorda/Documents/herd/impinity/API_DOCUMENTATION.md:1).

## Running Tests

```bash
php artisan test
```

## Notes

- The root `/` route serves a simple HTML landing page.
- All API routes are currently public.
- There is no frontend application in this repository.
