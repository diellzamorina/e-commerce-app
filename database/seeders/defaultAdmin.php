<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;


class defaultAdmin extends Seeder
{
    public function run()
    {
        $categoryElectronics = Category::create(['name' => 'Electronics']);
        $categoryClothing = Category::create(['name' => 'Clothing']);
        $categoryBeauty = Category::create(['name' => 'Beauty']);
        $categoryBooks = Category::create(['name' => 'Books']);
        $categorySports = Category::create(['name' => 'Sports']);

        // Seed products and associate them with categories
        $product1 = Product::create([
            'name' => 'Laptop',
            'description' => 'Powerful laptop for all your needs',
            'price' => 999.99,
            'quantity' => 50,
            'vendor_id' => 1,
            'category_id' => $categoryElectronics->id,
        ]);

        $product2 = Product::create([
            'name' => 'T-shirt',
            'description' => 'Comfortable cotton T-shirt for everyday wear',
            'price' => 19.99,
            'quantity' => 30,
            'vendor_id' => 1,
            'category_id' => $categoryClothing->id,
        ]);

        // Create more products with different categories
        $product3 = Product::create([
            'name' => 'Lipstick',
            'description' => 'Vibrant lipstick for a bold look',
            'price' => 14.99,
            'quantity' => 20,
            'vendor_id' => 1,
            'category_id' => $categoryBeauty->id,
        ]);

        $product4 = Product::create([
            'name' => 'Novel',
            'description' => 'Bestselling novel for book enthusiasts',
            'price' => 12.99,
            'quantity' => 100,
            'vendor_id' => 1,
            'category_id' => $categoryBooks->id,
        ]);

        $product5 = Product::create([
            'name' => 'Running Shoes',
            'description' => 'High-performance running shoes for athletes',
            'price' => 89.99,
            'quantity' => 40,
            'vendor_id' => 1,
            'category_id' => $categorySports->id,
        ]);
    }

}
