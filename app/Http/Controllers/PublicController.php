<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // Ambil kategori beserta produk yang tersedia
        $categories = Category::with(['products' => function($q) {
            $q->where('stock', '>', 0);
        }])->get();

        // Statistik penjualan
        $totalTransactions = \App\Models\Transaction::count();
        $totalProductsSold = TransactionDetail::sum('qty');
        $popularProducts = Product::withCount(['details as sold_qty' => function($q) {
            $q->select(\DB::raw('SUM(qty)'));
        }])->orderByDesc('sold_qty')->take(5)->get();

        return view('public.index', compact('categories', 'totalTransactions', 'totalProductsSold', 'popularProducts'));
    }
}
