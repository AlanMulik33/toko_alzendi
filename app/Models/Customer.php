<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;


class Customer extends Authenticatable {

    use Notifiable;

    protected $table = 'customers';

    // Masukkan hanya kolom yang boleh di-mass-assign
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'address',
        'password',
    ];

    // Sembunyikan password dari array/json
    protected $hidden = [
        'password',
        'remember_token'
    ];

    // Jika ingin otomatis hash saat set password lewat mass assignment:
    public function setPasswordAttribute($value)
    {
        // jika value sudah kosong, jangan set
        if ($value !== null && $value !== '') {
            $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
        }
    }

    
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}

