<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = ['name', 'category_id', 'price', 'stock', 'description'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function details() {
        return $this->hasMany(\App\Models\TransactionDetail::class, 'product_id');
    }
}

