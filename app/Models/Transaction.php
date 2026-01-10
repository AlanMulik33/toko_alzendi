<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    public function details() {
        return $this->hasMany(TransactionDetail::class);
    }
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
