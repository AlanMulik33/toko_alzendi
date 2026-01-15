@extends('layouts.app')

@section('title', 'Toko Alzendi - Beranda')

@section('content')
<div class="container-fluid mt-4">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-shop text-white" style="font-size: 1.8rem;"></i>
                        </div>
                        <div>
                            <h1 class="h2 mb-1 text-white">Selamat Datang di Toko Alzendi</h1>
                            <p class="mb-0 text-white opacity-75">
                                <i class="bi bi-tag me-1"></i>Belanja mudah, harga terjangkau
                            </p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="{{ route('customer.login') }}" class="btn btn-light">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                        <a href="{{ route('customer.register') }}" class="btn btn-outline-light">
                            <i class="bi bi-person-plus me-2"></i>Register
                        </a>
                    </div>
                </div>
                <div class="card-body bg-light bg-gradient p-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="lead mb-4">
                                Toko retail modern dengan berbagai produk berkualitas. 
                                Nikmati pengalaman berbelanja yang mudah dan aman.
                            </p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-primary px-3 py-2 rounded-pill">
                                    <i class="bi bi-truck me-1"></i>Gratis Ongkir
                                </span>
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-shield-check me-1"></i>Garansi Produk
                                </span>
                                <span class="badge bg-info px-3 py-2 rounded-pill">
                                    <i class="bi bi-clock me-1"></i>Buka 08.00-21.00
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="bi bi-cart-check" style="font-size: 4rem; color: var(--primary); opacity: 0.7;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                            <i class="bi bi-receipt text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h1 class="fw-bold text-primary mb-2">{{ number_format($totalTransactions) }}</h1>
                    <h5 class="text-muted mb-0">Total Transaksi</h5>
                    <small class="text-muted mt-2 d-block">
                        <i class="bi bi-arrow-up-circle text-success me-1"></i>
                        Transaksi berhasil diproses
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                            <i class="bi bi-box-seam text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h1 class="fw-bold text-success mb-2">{{ number_format($totalProductsSold) }}</h1>
                    <h5 class="text-muted mb-0">Produk Terjual</h5>
                    <small class="text-muted mt-2 d-block">
                        <i class="bi bi-check-circle text-success me-1"></i>
                        Produk telah sampai ke pelanggan
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-trophy text-info" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Produk Terpopuler</h5>
                            <small class="text-muted">Berdasarkan jumlah penjualan</small>
                        </div>
                    </div>
                    
                    @if(isset($popularProducts) && $popularProducts->count() > 0)
                        <div class="list-group">
                            @foreach($popularProducts as $pop)
                            <div class="list-group-item border-0 bg-transparent px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-fill">
                                        <strong class="d-block">{{ $pop->name }}</strong>
                                        <small class="text-muted">Terjual {{ $pop->sold_qty ?? 0 }} unit</small>
                                    </div>
                                    <span class="badge bg-info rounded-pill px-3 py-2">
                                        <i class="bi bi-fire me-1"></i>Populer
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-info-circle" style="font-size: 2rem; color: var(--gray);"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada data produk populer</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 text-white"><i class="bi bi-tags me-2"></i>Produk Berdasarkan Kategori</h3>
                    <small class="text-white opacity-75">
                        {{ $categories->count() }} kategori tersedia
                    </small>
                </div>
                <div class="card-body p-4">
                    @if($categories->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-tags" style="font-size: 3rem; color: var(--gray);"></i>
                            <h4 class="mt-3 text-muted">Belum ada kategori produk</h4>
                            <p class="text-muted">Tidak ada produk yang tersedia saat ini</p>
                        </div>
                    @else
                        <div class="accordion" id="categoriesAccordion">
                            @foreach($categories as $index => $cat)
                            <div class="accordion-item border-0 mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#category{{ $index }}" 
                                            aria-expanded="false" 
                                            aria-controls="category{{ $index }}">
                                        <div class="d-flex align-items-center w-100">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-tag text-primary"></i>
                                            </div>
                                            <div class="flex-fill">
                                                <strong class="d-block">{{ $cat->name }}</strong>
                                                <small class="text-muted">{{ $cat->products->count() }} produk tersedia</small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $cat->products->count() }}
                                            </span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="category{{ $index }}" 
                                     class="accordion-collapse collapse" 
                                     data-bs-parent="#categoriesAccordion">
                                    <div class="accordion-body p-0">
                                        @if($cat->products->count())
                                            <div class="table-responsive">
                                                <table class="table table-custom mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th class="text-center">Stok</th>
                                                            <th class="text-end">Harga</th>
                                                            <th class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($cat->products as $product)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                                         style="width: 36px; height: 36px;">
                                                                        <i class="bi bi-box text-primary"></i>
                                                                    </div>
                                                                    <div>
                                                                        <strong class="d-block">{{ $product->name }}</strong>
                                                                        @if($product->description)
                                                                            <small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge @if($product->stock > 20) bg-success 
                                                                                 @elseif($product->stock > 0) bg-warning 
                                                                                 @else bg-danger @endif 
                                                                        rounded-pill px-3 py-2">
                                                                    {{ $product->stock }}
                                                                </span>
                                                            </td>
                                                            <td class="text-end fw-bold text-primary">
                                                                Rp{{ number_format($product->price, 0, ',', '.') }}
                                                            </td>
                                                            <td class="text-center">
                                                                @if($product->stock > 20)
                                                                    <span class="badge bg-success bg-opacity-20 text-success px-3 py-2 rounded-pill">
                                                                        <i class="bi bi-check-circle me-1"></i>Tersedia
                                                                    </span>
                                                                @elseif($product->stock > 0)
                                                                    <span class="badge bg-warning bg-opacity-20 text-warning px-3 py-2 rounded-pill">
                                                                        <i class="bi bi-exclamation-triangle me-1"></i>Terbatas
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-danger bg-opacity-20 text-danger px-3 py-2 rounded-pill">
                                                                        <i class="bi bi-x-circle me-1"></i>Habis
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="bi bi-box" style="font-size: 2rem; color: var(--gray);"></i>
                                                <p class="text-muted mb-0 mt-2">Tidak ada produk tersedia di kategori ini</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-stars me-2"></i>Mengapa Memilih Toko Alzendi?</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 70px; height: 70px;">
                                    <i class="bi bi-shield-check text-primary" style="font-size: 1.8rem;"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Produk Berkualitas</h5>
                                <p class="text-muted small">Barang asli dengan garansi resmi</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 70px; height: 70px;">
                                    <i class="bi bi-truck text-success" style="font-size: 1.8rem;"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Pengiriman Cepat</h5>
                                <p class="text-muted small">Gratis ongkir untuk wilayah tertentu</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 70px; height: 70px;">
                                    <i class="bi bi-headset text-warning" style="font-size: 1.8rem;"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Layanan 24/7</h5>
                                <p class="text-muted small">Customer service siap membantu</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 70px; height: 70px;">
                                    <i class="bi bi-arrow-repeat text-info" style="font-size: 1.8rem;"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Garansi Produk</h5>
                                <p class="text-muted small">Pengembalian mudah dan aman</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="row">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-body text-center p-5" 
                     style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white;">
                    <h2 class="mb-4">Mulai Berbelanja Sekarang!</h2>
                    <p class="lead mb-4 opacity-90">
                        Bergabunglah dengan ribuan pelanggan yang puas dengan layanan kami
                    </p>
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        <a href="{{ route('customer.register') }}" class="btn btn-light btn-lg px-5">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </a>
                        <a href="{{ route('customer.login') }}" class="btn btn-outline-light btn-lg px-5">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login ke Akun
                        </a>
                    </div>
                    <div class="mt-4 pt-4 border-top border-white border-opacity-25">
                        <small class="opacity-75">
                            <i class="bi bi-info-circle me-1"></i>
                            Sudah memiliki akun? Login untuk melihat riwayat transaksi
                        </small>
                    </div>
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
    
    .badge.rounded-pill {
        padding: 0.5rem 1rem;
    }
    
    .accordion-button {
        background-color: #f8f9fa;
        border-radius: 10px !important;
        transition: all 0.3s ease;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary);
        box-shadow: none;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--primary);
    }
    
    .accordion-item {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .btn-light {
        background-color: white;
        color: var(--primary);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-light:hover {
        background-color: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
    }
    
    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>

<script>
    // Auto-collapse all except first on mobile
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth < 768) {
            const accordionButtons = document.querySelectorAll('.accordion-button');
            accordionButtons.forEach((button, index) => {
                if (index > 0) {
                    button.classList.add('collapsed');
                    const target = button.getAttribute('data-bs-target');
                    const collapseElement = document.querySelector(target);
                    if (collapseElement) {
                        collapseElement.classList.remove('show');
                    }
                }
            });
        }
    });
</script>
@endsection