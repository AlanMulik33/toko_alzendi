@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <h1>👋 Selamat Datang, {{ auth('web')->user()->name }}!</h1>
            <p class="text-muted">Kelola toko Anda dari dashboard ini</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <h2>📦</h2>
                    <h5 class="card-title">Manage Products</h5>
                    <p class="card-text text-muted">Kelola produk toko Anda</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">Buka</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <h2>🏷️</h2>
                    <h5 class="card-title">Manage Categories</h5>
                    <p class="card-text text-muted">Atur kategori produk</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-success btn-sm">Buka</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <h2>💳</h2>
                    <h5 class="card-title">View Transactions</h5>
                    <p class="card-text text-muted">Lihat semua transaksi</p>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-info btn-sm">Buka</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <h2>📊</h2>
                    <h5 class="card-title">Dashboard Laporan</h5>
                    <p class="card-text text-muted">Analytics & Grafik</p>
                    <a href="{{ route('report.dashboard') }}" class="btn btn-warning btn-sm">Buka</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection