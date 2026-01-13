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
        // Pastikan ada kategori
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $categories = collect([
                Category::create(['name' => 'Elektronik']),
                Category::create(['name' => 'Pakaian']),
                Category::create(['name' => 'Makanan']),
            ]);
        }

        $products = [
            [
                'name' => 'Laptop Gaming',
                'description' => 'Laptop untuk gaming dengan spesifikasi tinggi',
                'price' => 15000000,
                'stock' => 10,
                'category_id' => $categories->first()->id,
            ],
            [
                'name' => 'Smartphone Android',
                'description' => 'Smartphone dengan kamera 64MP',
                'price' => 5000000,
                'stock' => 25,
                'category_id' => $categories->first()->id,
            ],
            [
                'name' => 'Kaos Polos',
                'description' => 'Kaos polos katun premium',
                'price' => 75000,
                'stock' => 50,
                'category_id' => $categories->skip(1)->first()->id,
            ],
            [
                'name' => 'Celana Jeans',
                'description' => 'Celana jeans slim fit',
                'price' => 200000,
                'stock' => 30,
                'category_id' => $categories->skip(1)->first()->id,
            ],
            [
                'name' => 'Nasi Goreng',
                'description' => 'Nasi goreng spesial dengan telur',
                'price' => 25000,
                'stock' => 100,
                'category_id' => $categories->last()->id,
            ],
            [
                'name' => 'Ayam Bakar',
                'description' => 'Ayam bakar dengan bumbu rempah',
                'price' => 35000,
                'stock' => 75,
                'category_id' => $categories->last()->id,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}