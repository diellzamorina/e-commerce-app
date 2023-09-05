<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $product = new Product([
            'name' => 'Sample Product',
            'description' => 'This is a sample product',
            'price' => 19.99,
            'quantity' => 50,
            'category_id' => 1, // Set the category ID here
        ]);
        $product->save();
        
    }
}
