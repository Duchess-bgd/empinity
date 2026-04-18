<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('a category can have many products', function () {
    $category = Category::create(['name' => 'Sports']);

    $first = Product::create([
        'category_id' => $category->id,
        'name' => 'Basketball',
        'price' => 30.00,
        'stock' => 5,
    ]);

    $second = Product::create([
        'category_id' => $category->id,
        'name' => 'Soccer Ball',
        'price' => 25.00,
        'stock' => 8,
    ]);

    expect($category->products)->toHaveCount(2)
        ->and($category->products->pluck('id')->all())->toEqualCanonicalizing([$first->id, $second->id]);
});

test('a product belongs to a category and applies casts correctly', function () {
    $category = Category::create(['name' => 'Computers']);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Keyboard',
        'price' => 49.99,
        'stock' => 7,
    ]);

    $product->refresh();

    expect($product->category->id)->toBe($category->id)
        ->and($product->price)->toBe('49.99')
        ->and($product->stock)->toBe(7);
});
