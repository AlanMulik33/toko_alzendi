<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Customer;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('customer')->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('transactions.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total' => 'required|numeric|min:0',
            'items' => 'required'
        ]);

        DB::transaction(function() use ($request) {
            // Parse items jika string JSON
            $items = is_string($request->items) ? json_decode($request->items, true) : $request->items;
            
            // Validasi ada items
            if(empty($items)) {
                throw new \Exception('Minimal 1 item harus ditambahkan');
            }

            // Hitung total dari items
            $total = 0;
            foreach($items as $item) {
                if(!empty($item['price']) && !empty($item['qty'])) {
                    $total += (float)$item['price'] * (int)$item['qty'];
                }
            }

            // Buat transaksi
            $trx = Transaction::create([
                'customer_id' => $request->customer_id,
                'date' => now(),
                'total' => $total > 0 ? $total : (float)$request->total
            ]);

            // Buat detail transaksi dan update stock
            foreach($items as $item) {
                // Validasi item
                if(empty($item['product_id']) || empty($item['qty']) || empty($item['price'])) {
                    continue;
                }

                // Buat detail
                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'product_id' => (int)$item['product_id'],
                    'qty' => (int)$item['qty'],
                    'price' => (float)$item['price']
                ]);

                // Update stock produk
                $product = Product::find((int)$item['product_id']);
                if($product) {
                    $product->decrement('stock', (int)$item['qty']);
                }
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dibuat');
    }

    public function show(string $id)
    {
        $transaction = Transaction::with('customer', 'details.product')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::with('details')->findOrFail($id);
        
        DB::transaction(function() use ($transaction) {
            // Restore stock untuk setiap detail
            foreach($transaction->details as $detail) {
                $product = Product::find($detail->product_id);
                if($product) {
                    $product->increment('stock', $detail->qty);
                }
            }
            
            // Hapus transaksi (detail akan terhapus otomatis via cascade)
            $transaction->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus');
    }
}
