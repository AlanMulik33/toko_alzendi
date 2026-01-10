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
        DB::transaction(function() use ($request) {
            $trx = Transaction::create([
                'customer_id'=>$request->customer_id,
                'date'=>now(),
                'total'=>$request->total
            ]);

            foreach($request->items as $item){
                TransactionDetail::create([
                    'transaction_id'=>$trx->id,
                    'product_id'=>$item['product_id'],
                    'qty'=>$item['qty'],
                    'price'=>$item['price']
                ]);

                Product::where('id',$item['product_id'])
                    ->decrement('stock', $item['qty']);
            }
        });

        return redirect()->route('transactions.index');
    }

    public function show(string $id)
    {
        $transaction = Transaction::with('customer', 'details.product')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function destroy(string $id)
    {
        Transaction::findOrFail($id)->delete();
        return redirect()->route('transactions.index');
    }
}
