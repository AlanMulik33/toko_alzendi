<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\AdminAuthController;

// Public routes (guest only)
Route::get('/', function () {
    return view('welcome');
});

// Admin auth
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login']);
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Customer auth
Route::get('customer/login', [CustomerAuthController::class,'showLoginForm'])->name('customer.login');
Route::post('customer/login', [CustomerAuthController::class,'login']);
Route::post('customer/logout', [CustomerAuthController::class,'logout'])->name('customer.logout');
Route::get('customer/register', [CustomerAuthController::class,'showRegisterForm'])->name('customer.register');
Route::post('customer/register', [CustomerAuthController::class,'register']);

// Customer-only routes
Route::middleware('auth:customer')->group(function(){
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
    // Customer dapat melihat transaksi miliknya dan membuat transaksi baru
    Route::get('/transactions', [TransactionController::class,'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class,'store'])->name('transactions.store');
    Route::get('/transactions/create', [TransactionController::class,'create'])->name('transactions.create');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{id}/nota', [TransactionController::class, 'nota'])->name('transactions.nota');
});

// Admin-only routes
Route::middleware(['auth:web', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    // Admin dapat mengelola produk, kategori, dan melihat semua transaksi
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    // Admin transaction routes (manual untuk menghindari konflik dengan customer routes)
    Route::get('/admin/transactions', [TransactionController::class,'index'])->name('admin.transactions.index');
    Route::get('/admin/transactions/{transaction}', [TransactionController::class,'show'])->name('admin.transactions.show');
    Route::get('/admin/transactions/{transaction}/edit', [TransactionController::class,'edit'])->name('admin.transactions.edit');
    Route::put('/admin/transactions/{transaction}', [TransactionController::class,'update'])->name('admin.transactions.update');
    Route::delete('/admin/transactions/{transaction}', [TransactionController::class,'destroy'])->name('admin.transactions.destroy');
    Route::get('/report/transactions/pdf', [ReportController::class, 'transactionsPdf'])->name('report.transactions.pdf');
});

Route::get('/', function () {
    return view('welcome');
});
