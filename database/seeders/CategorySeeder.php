<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Electronics',
            'description' => 'Devices such as phones, laptops, and more.',
        ]);

        Category::create([
            'name' => 'Furniture',
            'description' => 'Furniture items like chairs, tables, and sofas.',
        ]);
    }
}
