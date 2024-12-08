<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear productos
        $category = Category::where('name', 'Electronics')->first();
        $category2 = Category::where('name', 'Furniture')->first();

        Product::create([
            'name' => 'Laptop',
            'description' => 'A powerful laptop with 16GB RAM and 512GB SSD.',
            'price' => 1000.00,
            'stock' => 50,
            'category_id' => $category->id
        ]);

        Product::create([
            'name' => 'Office Chair',
            'description' => 'Ergonomic chair for office use.',
            'price' => 150.00,
            'stock' => 30,
            'category_id' => $category2->id
        ]);
    }
}
