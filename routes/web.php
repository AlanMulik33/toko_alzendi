<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;


Route::resource('products', ProductController::class);
Route::resource('transactions', TransactionController::class);
Route::get('/transactions/{id}/nota', [TransactionController::class, 'nota'])->name('transactions.nota');
Route::get('/report/transactions/pdf', [ReportController::class, 'transactionsPdf'])->name('report.transactions.pdf');
Route::resource('customers', CustomerController::class);
Route::resource('categories', CategoryController::class);



Route::get('/', function () {
    return view('welcome');
});
