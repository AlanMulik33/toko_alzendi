<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Minuman', 'description' => 'Berbagai macam minuman'],
            ['name' => 'Snack', 'description' => 'Makanan ringan dan camilan'],
            ['name' => 'Perlengkapan Tulis', 'description' => 'Alat tulis dan perlengkapan sekolah'],
            ['name' => 'Perlengkapan Mandi', 'description' => 'Sabun, shampoo, dan perlengkapan mandi'],
            ['name' => 'Bahan Baku Dapur', 'description' => 'Bahan-bahan untuk memasak'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
