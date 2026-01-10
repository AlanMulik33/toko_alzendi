<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;


Route::resource('products', ProductController::class);
Route::get('/report/chart', [ReportController::class, 'chart'])->name('report.chart');
Route::resource('transactions', TransactionController::class);


Route::get('/', function () {
    return view('welcome');
});
