<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // Ambil semua kategori beserta produk yang tersedia (stock > 0)
        $categories = Category::with(['products' => function($query) {
            $query->where('stock', '>', 0);
        }])->get();

        return view('customer.dashboard', compact('categories'));
    }
}
