@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header Welcome -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-person-circle text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 text-white">Selamat Datang, {{ auth('customer')->user()->name }}!</h1>
                        <p class="mb-0 text-white opacity-75">
                            <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>
                <div class="card-body bg-light bg-gradient">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-envelope text-primary me-2"></i>
                                <strong>Email:</strong> {{ auth('customer')->user()->email }}
                            </p>
                            @if(auth('customer')->user()->phone)
                            <p class="mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <strong>Telepon:</strong> {{ auth('customer')->user()->phone }}
                            </p>
                            @endif
                            @if(auth('customer')->user()->address)
                            <p class="mb-0">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <strong>Alamat:</strong> {{ Str::limit(auth('customer')->user()->address, 80) }}
                            </p>
                            @endif
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="d-flex flex-column flex-md-row gap-3 justify-content-md-end">
                                <a href="{{ route('transactions.create') }}" class="btn btn-primary-custom">
                                    <i class="bi bi-cart-plus me-2"></i>Buat Transaksi Baru
                                </a>
                                <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-list-check me-2"></i>Lihat Transaksi Saya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Categories -->
    <div class="row">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white"><i class="bi bi-tags me-2"></i>Produk Tersedia per Kategori</h3>
                    <span class="badge bg-light text-dark">
                        {{ isset($categories) ? $categories->count() : 0 }} Kategori
                    </span>
                </div>
                <div class="card-body p-4">
                    @if(isset($categories) && $categories->count())
                        <div class="row g-4">
                            @foreach($categories as $category)
                            <div class="col-md-6 col-lg-4">
                                <div class="card card-custom shadow-sm h-100">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="bi bi-tag text-primary me-2"></i>{{ $category->name }}
                                        </h5>
                                        <span class="badge bg-primary">
                                            {{ $category->products->count() }} produk
                                        </span>
                                    </div>
                                    <div class="card-body p-0">
                                        @if($category->products->count())
                                            <div class="list-group list-group-flush">
                                                @foreach($category->products->take(3) as $product)
                                                <div class="list-group-item border-0">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-fill">
                                                            <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                                <span class="badge bg-light text-dark">
                                                                    Stok: {{ $product->stock }}
                                                                </span>
                                                                <span class="fw-bold text-primary">
                                                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                                                </span>
                                                            </div>
                                                            @if($product->description)
                                                            <small class="text-muted d-block mt-1">
                                                                {{ Str::limit($product->description, 60) }}
                                                            </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                
                                                @if($category->products->count() > 3)
                                                <div class="list-group-item border-0 text-center bg-light">
                                                    <small class="text-muted">
                                                        + {{ $category->products->count() - 3 }} produk lainnya
                                                    </small>
                                                </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="bi bi-box text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0 mt-2">Tidak ada produk tersedia</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-tags" style="font-size: 3rem; color: var(--gray);"></i>
                            <h5 class="mt-3 text-muted">Belum ada kategori produk</h5>
                            <p class="text-muted">Hubungi admin untuk informasi lebih lanjut</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-custom tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.03) !important;
    }
    
    .table-custom tbody tr td {
        vertical-align: middle;
        padding: 1rem;
    }
    
    .table-custom thead th {
        background-color: #f8f9fa !important;
        color: var(--dark) !important;
        border-bottom: 2px solid var(--primary);
        font-weight: 600;
        padding: 1rem;
    }
    
    .card.shadow-sm {
        transition: transform 0.3s ease;
    }
    
    .card.shadow-sm:hover {
        transform: translateY(-5px);
    }
    
    .list-group-item {
        padding: 1rem;
        border-color: var(--gray-light);
    }
    
    .btn.text-start {
        text-align: left;
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 10px;
    }
    
    .btn.text-start i {
        font-size: 1.2rem;
        margin-right: 1rem;
    }
</style>
@endsection