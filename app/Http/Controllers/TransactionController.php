<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
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

}
