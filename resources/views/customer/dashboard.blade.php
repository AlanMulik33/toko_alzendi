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

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-cart-check text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ isset($transactionStats['total']) ? $transactionStats['total'] : 0 }}</h3>
                            <p class="text-muted mb-0">Total Transaksi</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="{{ route('transactions.index') }}" class="text-decoration-none text-primary">
                        Lihat semua transaksi <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ isset($transactionStats['completed']) ? $transactionStats['completed'] : 0 }}</h3>
                            <p class="text-muted mb-0">Transaksi Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-clock-history text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ isset($transactionStats['pending']) ? $transactionStats['pending'] : 0 }}</h3>
                            <p class="text-muted mb-0">Transaksi Tertunda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white"><i class="bi bi-clock-history me-2"></i>Transaksi Terbaru</h3>
                    <a href="{{ route('transactions.index') }}" class="btn btn-light btn-sm">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-4">
                    @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td class="fw-bold">#{{ $transaction->transaction_code }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($transaction->status == 'completed')
                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Selesai
                                            </span>
                                        @elseif($transaction->status == 'pending')
                                            <span class="badge bg-warning rounded-pill px-3 py-2">
                                                <i class="bi bi-clock me-1"></i>Menunggu
                                            </span>
                                        @elseif($transaction->status == 'cancelled')
                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                            </span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                                {{ $transaction->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('transactions.show', $transaction->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-receipt" style="font-size: 3rem; color: var(--gray);"></i>
                        <h5 class="mt-3 text-muted">Belum ada transaksi</h5>
                        <p class="text-muted">Mulai berbelanja dengan membuat transaksi pertama Anda</p>
                        <a href="{{ route('transactions.create') }}" class="btn btn-primary-custom mt-2">
                            <i class="bi bi-cart-plus me-2"></i>Buat Transaksi Pertama
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h3>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="{{ route('transactions.create') }}" class="btn btn-primary-custom text-start">
                            <i class="bi bi-cart-plus me-2"></i>
                            <div>
                                <strong>Buat Transaksi Baru</strong>
                                <p class="mb-0 small">Mulai berbelanja produk toko</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-list-check me-2"></i>
                            <div>
                                <strong>Lihat Semua Transaksi</strong>
                                <p class="mb-0 small">Riwayat transaksi Anda</p>
                            </div>
                        </a>
                        
                        <div class="border-top pt-3 mt-2">
                            <h6 class="fw-bold mb-3">Kontak Toko</h6>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <span>(021) 1234-5678</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-envelope text-primary me-2"></i>
                                <span>support@tokoalzendi.com</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-primary me-2"></i>
                                <span>Buka: 08.00 - 21.00 WIB</span>
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
                                    <div class="card-footer bg-transparent">
                                        <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="bi bi-cart-plus me-1"></i>Beli Produk dari Kategori Ini
                                        </a>
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