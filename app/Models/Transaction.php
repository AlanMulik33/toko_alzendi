<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $fillable = ['customer_id', 'date', 'total', 'notes'];
    protected $casts = [
        'date' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function details() {
        return $this->hasMany(TransactionDetail::class);
    }
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
