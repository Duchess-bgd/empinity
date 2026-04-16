<?php

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;

test('products endpoint returns seeded products with category data', function () {
    $this->seed([
        CategorySeeder::class,
        ProductSeeder::class,
    ]);

    $response = $this->getJson('/api/products');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('meta.total', 10)
        ->assertJsonFragment(['name' => 'Laptop HP Pavilion'])
        ->assertJsonStructure([
            'success',
            'data' => [
                ['id', 'category_id', 'name', 'price', 'stock', 'created_at', 'updated_at', 'category'],
            ],
            'meta' => ['total', 'filters'],
        ]);
});

test('products endpoint supports search and category filters', function () {
    $this->seed([
        CategorySeeder::class,
        ProductSeeder::class,
    ]);

    $electronics = Category::where('name', 'Electronics')->firstOrFail();

    $response = $this->getJson("/api/products?search=iPhone&category_id={$electronics->id}");

    $response->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonPath('meta.filters.search', 'iPhone')
        ->assertJsonPath('meta.filters.category_id', (string) $electronics->id)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['name' => 'iPhone 15']);
});

test('products can be created', function () {
    $category = Category::create(['name' => 'Accessories']);

    $response = $this->postJson('/api/products', [
        'name' => 'USB-C Cable',
        'category_id' => $category->id,
        'price' => 12.99,
        'stock' => 100,
    ]);

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'USB-C Cable')
        ->assertJsonPath('data.category.id', $category->id);

    $this->assertDatabaseHas('products', [
        'name' => 'USB-C Cable',
        'category_id' => $category->id,
        'stock' => 100,
    ]);
});

test('product creation validates required fields', function () {
    $response = $this->postJson('/api/products', []);

    $response->assertUnprocessable()
        ->assertJsonPath('success', false)
        ->assertJsonStructure([
            'success',
            'message',
            'errors' => ['name', 'category_id', 'price', 'stock'],
        ]);
});

test('products can be updated', function () {
    $category = Category::create(['name' => 'Books']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Original Name',
        'price' => 19.99,
        'stock' => 5,
    ]);

    $response = $this->putJson("/api/products/{$product->id}", [
        'price' => 24.99,
        'stock' => 9,
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.price', '24.99')
        ->assertJsonPath('data.stock', 9);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'price' => 24.99,
        'stock' => 9,
    ]);
});

test('products can be deleted', function () {
    $category = Category::create(['name' => 'Garden']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Hose',
        'price' => 15.50,
        'stock' => 3,
    ]);

    $response = $this->deleteJson("/api/products/{$product->id}");

    $response->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});
