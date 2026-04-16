# Impinity API Documentation

## Overview

Impinity is a Laravel 13 API-only backend for a simple product catalog.

The API exposes:

- category listing
- product listing with optional filters
- product creation
- product updates
- product deletion

The application uses a relational model:

- a `Category` has many `Product` records
- a `Product` belongs to one `Category`

## Stack

- PHP `^8.3`
- Laravel `^13.0`
- Pest for testing
- SQLite in-memory for automated tests

## Project Structure

- `routes/api.php`: public API routes
- `app/Http/Controllers/Api`: API controllers
- `app/Models`: Eloquent models
- `database/migrations`: schema definition
- `database/seeders`: sample catalog data
- `tests/Feature/Api`: API endpoint tests

## Local Setup

### 1. Install dependencies

```bash
composer install
```

### 2. Create environment file

```bash
copy .env.example .env
```

If the file already exists, keep your current `.env`.

### 3. Generate application key

```bash
php artisan key:generate
```

### 4. Configure database

Set the database connection in `.env` to any database supported by Laravel. Example for SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### 5. Run migrations and seed demo data

```bash
php artisan migrate --seed
```

This seeds the catalog with example categories and products through `DatabaseSeeder`.

### 6. Start the local server

```bash
php artisan serve
```

If you use Herd or another local environment, use that base URL instead of the default Laravel URL.

## Seeded Demo Data

The default seeders create these categories:

- Electronics
- Clothing
- Books
- Home & Garden

The seeded products include examples such as:

- Laptop HP Pavilion
- iPhone 15
- Sony Headphones
- Nike T-Shirt
- Laravel: The Complete Guide
- Indoor Plant

## Base URL

Examples in this document use:

```text
http://localhost:8000
```

If you run the app through Herd, your URL may look like:

```text
http://impinity.test
```

## Response Format

Successful responses use JSON. Most endpoints return this shape:

```json
{
  "success": true,
  "data": []
}
```

Product listing also includes a `meta` object:

```json
{
  "success": true,
  "data": [],
  "meta": {
    "total": 10,
    "filters": {
      "search": "Laptop",
      "category_id": "1"
    }
  }
}
```

Validation failures return HTTP `422 Unprocessable Entity`:

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": [
      "The name field is required."
    ]
  }
}
```

## API Endpoints

### `GET /api/categories`

Returns all categories.

Example request:

```bash
curl http://localhost:8000/api/categories
```

Example response:

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "created_at": "2026-04-16T10:00:00.000000Z",
      "updated_at": "2026-04-16T10:00:00.000000Z"
    }
  ]
}
```

### `GET /api/products`

Returns all products with their related category.

Supported query parameters:

- `search`: partial match against product `name`
- `category_id`: exact category filter

Example requests:

```bash
curl http://localhost:8000/api/products
curl "http://localhost:8000/api/products?search=laptop"
curl "http://localhost:8000/api/products?category_id=1"
curl "http://localhost:8000/api/products?search=iphone&category_id=1"
```

Example response:

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "name": "Laptop HP Pavilion",
      "price": "850.00",
      "stock": 15,
      "created_at": "2026-04-16T10:00:00.000000Z",
      "updated_at": "2026-04-16T10:00:00.000000Z",
      "category": {
        "id": 1,
        "name": "Electronics",
        "created_at": "2026-04-16T10:00:00.000000Z",
        "updated_at": "2026-04-16T10:00:00.000000Z"
      }
    }
  ],
  "meta": {
    "total": 1,
    "filters": {
      "search": "laptop",
      "category_id": "1"
    }
  }
}
```

### `POST /api/products`

Creates a product.

Request body:

```json
{
  "name": "USB-C Cable",
  "category_id": 1,
  "price": 12.99,
  "stock": 100
}
```

Validation rules:

- `name`: required, string, max 255 characters
- `category_id`: required, must exist in `categories`
- `price`: required, numeric, minimum `0.01`
- `stock`: required, integer, minimum `0`

Example request:

```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"USB-C Cable\",\"category_id\":1,\"price\":12.99,\"stock\":100}"
```

Successful response:

```json
{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "id": 11,
    "category_id": 1,
    "name": "USB-C Cable",
    "price": "12.99",
    "stock": 100,
    "created_at": "2026-04-16T10:00:00.000000Z",
    "updated_at": "2026-04-16T10:00:00.000000Z",
    "category": {
      "id": 1,
      "name": "Electronics",
      "created_at": "2026-04-16T10:00:00.000000Z",
      "updated_at": "2026-04-16T10:00:00.000000Z"
    }
  }
}
```

### `PUT /api/products/{id}`

Updates an existing product.

All fields are optional, but any provided field must pass validation.

Allowed fields:

- `name`
- `category_id`
- `price`
- `stock`

Example request:

```bash
curl -X PUT http://localhost:8000/api/products/1 \
  -H "Content-Type: application/json" \
  -d "{\"price\":899.99,\"stock\":20}"
```

Successful response:

```json
{
  "success": true,
  "message": "Product updated successfully",
  "data": {
    "id": 1,
    "category_id": 1,
    "name": "Laptop HP Pavilion",
    "price": "899.99",
    "stock": 20,
    "created_at": "2026-04-16T10:00:00.000000Z",
    "updated_at": "2026-04-16T10:05:00.000000Z",
    "category": {
      "id": 1,
      "name": "Electronics",
      "created_at": "2026-04-16T10:00:00.000000Z",
      "updated_at": "2026-04-16T10:00:00.000000Z"
    }
  }
}
```

### `DELETE /api/products/{id}`

Deletes a product by ID.

Example request:

```bash
curl -X DELETE http://localhost:8000/api/products/1
```

Successful response:

```json
{
  "success": true,
  "message": "Product deleted successfully"
}
```

## Status Codes

- `200 OK`: successful read, update, or delete
- `201 Created`: successful product creation
- `422 Unprocessable Entity`: validation error
- `404 Not Found`: product not found

## Testing

Run the automated test suite with:

```bash
php artisan test
```

The current tests cover:

- root landing page response
- category listing
- product listing
- product filtering
- product creation
- validation failures
- product updates
- product deletion

## Notes

- All API routes are currently public.
- There is no authentication or authorization layer in the current implementation.
- Product prices are returned as decimal strings by Eloquent casting.
- The root `/` page is a simple informational HTML page, not a frontend application.
