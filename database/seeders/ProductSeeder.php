<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics')->first();
        $clothing = Category::where('name', 'Clothing')->first();
        $books = Category::where('name', 'Books')->first();
        $home = Category::where('name', 'Home & Garden')->first();

        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Laptop HP Pavilion',
            'price' => 850.00,
            'stock' => 15,
        ]);

        Product::create([
            'category_id' => $electronics->id,
            'name' => 'iPhone 15',
            'price' => 999.00,
            'stock' => 8,
        ]);

        Product::create([
            'category_id' => $electronics->id,
            'name' => 'Sony Headphones',
            'price' => 120.00,
            'stock' => 25,
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Nike T-Shirt',
            'price' => 29.99,
            'stock' => 50,
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Levi\'s Jeans',
            'price' => 79.99,
            'stock' => 30,
        ]);

        Product::create([
            'category_id' => $clothing->id,
            'name' => 'Winter Jacket',
            'price' => 150.00,
            'stock' => 12,
        ]);

        Product::create([
            'category_id' => $books->id,
            'name' => 'Laravel: The Complete Guide',
            'price' => 45.00,
            'stock' => 20,
        ]);

        Product::create([
            'category_id' => $books->id,
            'name' => 'Vue.js Mastery',
            'price' => 35.00,
            'stock' => 15,
        ]);

        Product::create([
            'category_id' => $home->id,
            'name' => 'Garden Chair Set',
            'price' => 120.00,
            'stock' => 10,
        ]);

        Product::create([
            'category_id' => $home->id,
            'name' => 'Indoor Plant',
            'price' => 25.00,
            'stock' => 40,
        ]);
    }
}
