<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Customer;
use App\Services\QrisService;
use App\Services\CloudinaryService;


class TransactionController extends Controller
{
    /**
     * Form transaksi offline (kasir/admin)
     */
    public function offlineCreate()
    {
        $products = Product::all();
        return view('transactions.offline_create', compact('products'));
    }

    /**
     * Proses transaksi offline (kasir/admin)
     */
    public function offlineStore(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'items' => 'required',
            'uang_diterima' => 'required|numeric|min:0',
        ]);

        $items = is_string($request->items) ? json_decode($request->items, true) : $request->items;
        if (empty($items)) {
            return back()->withInput()->withErrors(['error' => 'Minimal 1 produk harus dipilih']);
        }

        $total = 0;
        foreach ($items as $item) {
            if (!empty($item['price']) && !empty($item['qty'])) {
                $total += (float)$item['price'] * (int)$item['qty'];
            }
        }

        if ($request->uang_diterima < $total) {
            return back()->withInput()->withErrors(['error' => 'Uang diterima kurang dari total belanja']);
        }

        // Simpan transaksi offline (tanpa customer terdaftar)
        $uangDiterima = $request->uang_diterima;
        $kembalian = $uangDiterima - $total;
        $trx = DB::transaction(function() use ($request, $items, $total, $uangDiterima, $kembalian) {
            $trx = Transaction::create([
                'customer_id' => null,
                'date' => now(),
                'total' => $total,
                'uang_diterima' => $uangDiterima,
                'kembalian' => $kembalian,
                'payment_method' => 'cash',
                'notes' => 'Offline customer: ' . $request->customer_name,
                'status' => 'completed',
            ]);

            foreach ($items as $item) {
                if (empty($item['product_id']) || empty($item['qty']) || empty($item['price'])) continue;
                $product = Product::find((int)$item['product_id']);
                if ($product && $product->stock >= (int)$item['qty']) {
                    TransactionDetail::create([
                        'transaction_id' => $trx->id,
                        'product_id' => (int)$item['product_id'],
                        'qty' => (int)$item['qty'],
                        'price' => (float)$item['price']
                    ]);
                    $product->decrement('stock', (int)$item['qty']);
                }
            }
            return $trx;
        });

        // Redirect ke nota
        return redirect()->route('admin.transactions.nota', $trx->id)
            ->with('success', 'Transaksi offline berhasil. Kembalian: Rp ' . number_format($request->uang_diterima - $total, 0, ',', '.'));
    }
    public function index(): \Illuminate\View\View
    {
        if (auth('customer')->check()) {
            /** @var \App\Models\Customer $customer */
            $customer = auth('customer')->user();
            $customerId = $customer->id ?? null;
            $transactions = Transaction::query()
                ->where('customer_id', $customerId)
                ->with('customer')
                ->paginate(15);
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
        try {
            // Tentukan customer_id berdasarkan guard
            $customer_id = null;
            $address_id = null;
            
            if (auth('customer')->check()) {
                $customer_id = auth('customer')->id();
                $address_id = $request->address_id;
            } else {
                $customer_id = $request->customer_id;
            }

            // Validasi
            $rules = [
                'total' => 'required|numeric|min:0',
                'items' => 'required',
                'payment_method' => 'required|in:cash,qris'
            ];
            if (!$customer_id) {
                $rules['customer_id'] = 'required|exists:customers,id';
            }
            if ($customer_id && !$address_id) {
                $rules['address_id'] = 'required|exists:customer_addresses,id';
            }
            $request->validate($rules);

            $trx = DB::transaction(function() use ($request, $customer_id, $address_id) {
                try {
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

                // Ambil alamat jika ada
                $addressData = [];
                if ($address_id) {
                    $address = \App\Models\CustomerAddress::find($address_id, ['*']);
                    if ($address && $address->customer_id === $customer_id) {
                        $addressData['address_id'] = $address_id;
                        $addressData['address_snapshot'] = "Label: {$address->label}\nAlamat: {$address->address}\nTelepon: {$address->phone}";
                    }
                }

                // Buat transaksi
                $trx = Transaction::create([
                    'customer_id' => $customer_id,
                    'date' => now(),
                    'total' => $total > 0 ? $total : (float)$request->total,
                    'payment_method' => $request->payment_method,
                    ...$addressData
                ]);

                // Generate QRIS jika metode pembayaran adalah QRIS
                if ($request->payment_method === 'qris') {
                    $finalTotal = $total > 0 ? $total : (float)$request->total;
                    $qrisCode = QrisService::generateSimpleQris($finalTotal, 'TRX' . $trx->id);
                    
                    if ($qrisCode) {
                        $trx->update(['qris_code' => $qrisCode]);
                        \Log::info('QRIS code generated', ['trx_id' => $trx->id, 'amount' => $finalTotal]);
                    } else {
                        \Log::warning('Failed to generate QRIS code', ['trx_id' => $trx->id]);
                    }
                }

                \Log::info('Transaction created', ['trx_id' => $trx->id]);

                // Buat detail transaksi dan update stock
                foreach($items as $item) {
                    // Validasi item
                    if(empty($item['product_id']) || empty($item['qty']) || empty($item['price'])) {
                        continue;
                    }

                    // Cek stock produk
                    $product = Product::find((int)$item['product_id'], ['*']);
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
                } catch (\Exception $e) {
                    \Log::error('Error in DB transaction', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                    throw $e; // Re-throw to rollback
                }
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
        
        // Authorization: customer hanya bisa lihat transaksi miliknya sendiri
        if (auth('customer')->check() && $transaction->customer_id !== auth('customer')->id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('transactions.show', compact('transaction'));
    }

    public function nota(string $id)
    {
        $transaction = Transaction::with(['customer.defaultAddress', 'details.product'])->findOrFail($id);
        
        // Authorization: customer hanya bisa lihat nota transaksinya sendiri
        if (auth('customer')->check() && $transaction->customer_id !== auth('customer')->id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('transactions.nota', compact('transaction'));
    }

    public function update(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);
        if (auth('web')->check()) {
            if ($request->input('action') === 'verify' && $transaction->status === 'pending') {
                $transaction->status = 'verified';
                $transaction->save();
                return back()->with('success', 'Transaksi berhasil diverifikasi!');
            }
            if ($request->input('action') === 'ship' && $transaction->status === 'verified') {
                $transaction->status = 'shipped';
                $transaction->save();
                return back()->with('success', 'Pesanan sudah dikirim!');
            }
        }
        if (auth('customer')->check() && $transaction->customer_id == auth('customer')->id()) {
            if ($request->input('action') === 'complete' && $transaction->status === 'shipped') {
                $transaction->status = 'completed';
                $transaction->save();
                // Kirim notifikasi ke semua admin
                foreach (\App\Models\User::all() as $admin) {
                    $admin->notify(new \App\Notifications\OrderCompletedNotification($transaction));
                }
                return back()->with('success', 'Pesanan telah dikonfirmasi selesai!');
            }
            // QRIS: upload bukti pembayaran
            if ($request->input('action') === 'pay' && $transaction->status === 'pending' && $transaction->payment_method === 'qris') {
                if (
                        $request->input('action') === 'pay' &&
                        $transaction->status === 'pending' &&
                        $transaction->payment_method === 'qris'
                    ) {
                        $request->validate([
                            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                        ]);

                        $imageUrl = CloudinaryService::upload(
                            $request->file('payment_proof'),
                            'payment_proofs'
                        );

                        $transaction->update([
                            'payment_proof' => $imageUrl,
                        ]);

                        return back()->with(
                            'success',
                            'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.'
                        );
                    }

            }
        }
        return back()->with('error', 'Aksi tidak valid atau status tidak sesuai.');
    }

    public function destroy(string $id)
    {
        $transaction = Transaction::with('details')->findOrFail($id);
        
        DB::transaction(function() use ($transaction) {
            // Restore stock untuk setiap detail
            foreach($transaction->details as $detail) {
                $product = Product::find($detail->product_id, ['*']);
                if($product) {
                    $product->increment('stock', $detail->qty);
                }
            }
            // Hapus transaksi (detail akan terhapus otomatis via cascade)
            $transaction->forceDelete();
        });
        return redirect()->route(auth('customer')->check() ? 'transactions.index' : 'admin.transactions.index')->with('success', 'Transaksi berhasil dihapus');
    }
}
