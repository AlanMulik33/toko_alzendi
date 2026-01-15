<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->get('category');

        // Query untuk ambil data produk yang dibeli per kategori
        $query = Transaction::with('details.product.category')
            ->selectRaw('categories.name as category_name, SUM(transaction_details.qty) as total_qty, COUNT(DISTINCT transactions.id) as transaction_count')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_qty');

        // Filter by kategori jika dipilih
        if ($selectedCategory && $selectedCategory !== 'all') {
            $query->where('categories.id', $selectedCategory);
        }

        $categoryData = $query->get();

        // Data untuk detail produk berdasarkan kategori filter
        $detailQuery = \App\Models\TransactionDetail::with('product.category')
            ->selectRaw('products.name as product_name, categories.name as category_name, SUM(transaction_details.qty) as total_qty, AVG(transaction_details.price) as avg_price')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('products.id', 'products.name', 'categories.id', 'categories.name')
            ->orderByDesc('total_qty');

        if ($selectedCategory && $selectedCategory !== 'all') {
            $detailQuery->where('categories.id', $selectedCategory);
        }

        $productDetails = $detailQuery->get();

        return view('reports.dashboard', compact('categories', 'categoryData', 'selectedCategory', 'productDetails'));
    }

    public function transactionsPdf()
    {
        $transactions = Transaction::with('customer', 'details.product')->get();
        $pdf = Pdf::loadView('reports.transactions', compact('transactions'));
        return $pdf->download('laporan-transaksi.pdf');
    }

    public function chart()
    {
        $data = Transaction::selectRaw('DATE(date) as tanggal, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = $data->pluck('tanggal');
        $totals = $data->pluck('total');

        return view('reports.chart', compact('labels', 'totals'));
    }

    public function downloadExcel(Request $request)
    {
        $selectedCategory = $request->get('category');
        $fileName = 'Laporan-Penjualan-' . now()->format('d-m-Y-His') . '.xlsx';
        
        return Excel::download(
            new SalesReportExport($selectedCategory),
            $fileName
        );
    }
}
