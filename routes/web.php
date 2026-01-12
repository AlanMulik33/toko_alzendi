<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\AdminAuthController;

Route::resource('products', ProductController::class);
Route::resource('transactions', TransactionController::class);
Route::get('/transactions/{id}/nota', [TransactionController::class, 'nota'])->name('transactions.nota');
Route::get('/report/transactions/pdf', [ReportController::class, 'transactionsPdf'])->name('report.transactions.pdf');
Route::resource('customers', CustomerController::class);
Route::resource('categories', CategoryController::class);

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
    // route transaksi yang hanya boleh diakses pelanggan login
    Route::post('/transactions', [TransactionController::class,'store'])->name('transactions.store');
});

// Admin-only routes
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('customers', CustomerController::class);
    Route::get('/report/transactions/pdf', [ReportController::class, 'transactionsPdf'])->name('report.transactions.pdf');
});

Route::get('/', function () {
    return view('welcome');
});
