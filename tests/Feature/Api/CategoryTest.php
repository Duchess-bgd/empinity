<?php

use Database\Seeders\CategorySeeder;

test('categories endpoint returns seeded categories', function () {
    $this->seed(CategorySeeder::class);

    $response = $this->getJson('/api/categories');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(4, 'data')
        ->assertJsonFragment(['name' => 'Electronics'])
        ->assertJsonFragment(['name' => 'Home & Garden']);
});

test('categories endpoint returns empty array when none exist', function () {
    $response = $this->getJson('/api/categories');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(0, 'data');
});
