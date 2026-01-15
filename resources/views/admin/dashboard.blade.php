@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header Welcome -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-speedometer2 text-white" style="font-size: 1.8rem;"></i>
                        </div>
                        <div>
                            <h1 class="h2 mb-1 text-white">Selamat Datang, {{ auth('web')->user()->name }}!</h1>
                            <p class="mb-0 text-white opacity-75">
                                <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.transactions.offline.create') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-person-plus me-2"></i>Tambah Customer Offline
                    </a>
                </div>
                <div class="card-body bg-light bg-gradient">
                    <p class="lead mb-0">
                        <i class="bi bi-gear me-2"></i>Kelola toko Anda dari dashboard ini dengan mudah dan efisien
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    @if(isset($stats) && count($stats) > 0)
    <div class="row g-4 mb-4">
        @foreach($stats as $stat)
        <div class="col-md-3">
            <div class="card card-custom shadow-soft h-100 border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary), var(--secondary));">
                            <i class="bi bi-{{ $stat['icon'] ?? 'box' }} text-white" style="font-size: 1.3rem;"></i>
                        </div>
                        @if(isset($stat['badge']) && $stat['badge'] > 0)
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            {{ $stat['badge'] }}
                        </span>
                        @endif
                    </div>
                    <h3 class="fw-bold mb-2">{{ number_format($stat['count']) }}</h3>
                    <h6 class="text-muted mb-3">{{ $stat['title'] }}</h6>
                    <a href="{{ $stat['url'] }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-arrow-right me-1"></i>Kelola
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('products.index') }}" class="card card-custom shadow-soft border-0 text-decoration-none h-100">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                                            <i class="bi bi-box text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-2">Kelola Produk</h5>
                                    <p class="text-muted mb-3">Tambah, edit, atau hapus produk toko</p>
                                    <span class="badge bg-primary bg-opacity-20 text-primary px-3 py-2 rounded-pill">
                                        <i class="bi bi-arrow-right me-1"></i>Akses
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('categories.index') }}" class="card card-custom shadow-soft border-0 text-decoration-none h-100">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                                            <i class="bi bi-tags text-success" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-2">Kelola Kategori</h5>
                                    <p class="text-muted mb-3">Atur kategori dan pengelompokan produk</p>
                                    <span class="badge bg-success bg-opacity-20 text-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-arrow-right me-1"></i>Akses
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('admin.transactions.index') }}" class="card card-custom shadow-soft border-0 text-decoration-none h-100">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                                            <i class="bi bi-receipt text-info" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-2">Transaksi
                                        @if(isset($pendingCount) && $pendingCount > 0)
                                        <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-3">Lihat dan kelola semua transaksi</p>
                                    <span class="badge bg-info bg-opacity-20 text-info px-3 py-2 rounded-pill">
                                        <i class="bi bi-arrow-right me-1"></i>Akses
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="{{ route('report.dashboard') }}" class="card card-custom shadow-soft border-0 text-decoration-none h-100">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center p-3">
                                            <i class="bi bi-bar-chart text-warning" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                    <h5 class="fw-bold mb-2">Dashboard Laporan</h5>
                                    <p class="text-muted mb-3">Analytics, grafik, dan insight toko</p>
                                    <span class="badge bg-warning bg-opacity-20 text-warning px-3 py-2 rounded-pill">
                                        <i class="bi bi-arrow-right me-1"></i>Akses
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h3>
                </div>
                <div class="card-body p-4">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                    <div class="timeline">
                        @foreach($recentActivities as $activity)
                        <div class="timeline-item d-flex mb-3">
                            <div class="timeline-marker rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--secondary));">
                                <i class="bi bi-{{ $activity['icon'] ?? 'circle' }} text-white"></i>
                            </div>
                            <div class="timeline-content flex-fill">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="fw-bold mb-1">{{ $activity['title'] }}</h6>
                                    <small class="text-muted">{{ $activity['time'] }}</small>
                                </div>
                                <p class="text-muted mb-0">{{ $activity['description'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-activity" style="font-size: 3rem; color: var(--gray);"></i>
                        <h5 class="mt-3 text-muted">Belum ada aktivitas</h5>
                        <p class="text-muted">Mulai kelola toko Anda untuk melihat aktivitas di sini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom shadow-soft border-0 h-100">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-info-circle me-2"></i>Info Toko</h3>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-shop text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Toko Alzendi</h5>
                            <p class="text-muted mb-0">Dashboard Admin</p>
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item bg-transparent border-bottom px-0 py-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Produk</span>
                                <span class="fw-bold">{{ isset($stats['products']) ? number_format($stats['products']['count']) : '0' }}</span>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-bottom px-0 py-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Kategori</span>
                                <span class="fw-bold">{{ isset($stats['categories']) ? number_format($stats['categories']['count']) : '0' }}</span>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent border-bottom px-0 py-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Transaksi Hari Ini</span>
                                <span class="fw-bold">{{ isset($stats['today_transactions']) ? number_format($stats['today_transactions']['count']) : '0' }}</span>
                            </div>
                        </div>
                        <div class="list-group-item bg-transparent px-0 py-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Customer Terdaftar</span>
                                <span class="fw-bold">{{ isset($stats['customers']) ? number_format($stats['customers']['count']) : '0' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('admin.logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="btn btn-outline-danger w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout Admin
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 1rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-marker {
        flex-shrink: 0;
        z-index: 1;
    }
    
    .timeline-content {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid var(--gray-light);
    }
    
    .card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }
    
    .card a:hover {
        text-decoration: none;
    }
    
    .list-group-item {
        border-color: var(--gray-light);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh stats every 60 seconds
        setInterval(function() {
            fetch('/admin/dashboard/stats')
                .then(response => response.json())
                .then(data => {
                    // Update stats if needed
                    console.log('Stats updated:', data);
                });
        }, 60000);
    });
</script>
@endsection