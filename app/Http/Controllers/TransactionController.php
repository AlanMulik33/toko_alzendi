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
        if (auth('customer')->check()) {
            $transactions = Transaction::where('customer_id', auth('customer')->id())->with('customer')->paginate(15);
        } else {
            $transactions = Transaction::with('customer')->paginate(15);
        }
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $customers = auth('customer')->check() ? [] : Customer::all();
        $products = Product::all();
        return view('transactions.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        \Log::info('Transaction store called', $request->all());

        try {
            // Tentukan customer_id berdasarkan guard
            $customer_id = null;
            if (auth('customer')->check()) {
                $customer_id = auth('customer')->id();
            } else {
                $customer_id = $request->customer_id;
            }

            \Log::info('Customer ID determined', ['customer_id' => $customer_id]);

            // Validasi
            $rules = [
                'total' => 'required|numeric|min:0',
                'items' => 'required',
                'payment_method' => 'required|in:cash,qris'
            ];
            if (!$customer_id) {
                $rules['customer_id'] = 'required|exists:customers,id';
            }
            $request->validate($rules);

            \Log::info('Validation passed');

            $trx = DB::transaction(function() use ($request, $customer_id) {
                dd('Inside DB transaction', $request->all()); // Debug
                
                // Parse items jika string JSON
                $items = is_string($request->items) ? json_decode($request->items, true) : $request->items;
                
                \Log::info('Items parsed', ['items' => $items]);
                
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

                \Log::info('Total calculated', ['total' => $total]);

                // Buat transaksi
                $trx = Transaction::create([
                    'customer_id' => $customer_id,
                    'date' => now(),
                    'total' => $total > 0 ? $total : (float)$request->total,
                    'payment_method' => $request->payment_method
                ]);

                \Log::info('Transaction created', ['trx_id' => $trx->id]);

                // Buat detail transaksi dan update stock
                foreach($items as $item) {
                    // Validasi item
                    if(empty($item['product_id']) || empty($item['qty']) || empty($item['price'])) {
                        continue;
                    }

                    // Cek stock produk
                    $product = Product::find((int)$item['product_id']);
                    if(!$product) {
                        throw new \Exception('Produk tidak ditemukan: ' . $item['product_id']);
                    }
                    if($product->stock < (int)$item['qty']) {
                        throw new \Exception('Stock tidak cukup untuk produk: ' . $product->name . ' (tersedia: ' . $product->stock . ', diminta: ' . $item['qty'] . ')');
                    }

                    \Log::info('Creating transaction detail', ['product_id' => $item['product_id'], 'qty' => $item['qty']]);

                    // Buat detail
                    TransactionDetail::create([
                        'transaction_id' => $trx->id,
                        'product_id' => (int)$item['product_id'],
                        'qty' => (int)$item['qty'],
                        'price' => (float)$item['price']
                    ]);

                    // Update stock produk
                    $product->decrement('stock', (int)$item['qty']);
                    
                    \Log::info('Stock decremented', ['product_id' => $item['product_id'], 'new_stock' => $product->fresh()->stock]);
                }
                
                return $trx;
            });

            \Log::info('Transaction completed', ['trx_id' => $trx->id]);

            return redirect()->route('transactions.nota', $trx->id)->with('success', 'Transaksi berhasil dibuat');
        } catch (\Exception $e) {
            \Log::error('Transaction failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors(['error' => 'Gagal membuat transaksi: ' . $e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        $transaction = Transaction::with('customer', 'details.product')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function nota(string $id)
    {
        $transaction = Transaction::with('customer', 'details.product')->findOrFail($id);
        return view('transactions.nota', compact('transaction'));
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
