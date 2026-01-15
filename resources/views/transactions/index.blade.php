@extends('layouts.app')

@section('title', auth('customer')->check() ? 'My Transactions' : 'Daftar Transaksi')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-receipt text-white" style="font-size: 1.8rem;"></i>
                        </div>
                        <div>
                            @if(auth('customer')->check())
                            <h1 class="h2 mb-1 text-white">Riwayat Transaksi Saya</h1>
                            <p class="mb-0 text-white opacity-75">Daftar semua transaksi yang Anda lakukan</p>
                            @else
                            <h1 class="h2 mb-1 text-white">Manajemen Transaksi</h1>
                            <p class="mb-0 text-white opacity-75">Kelola semua transaksi pelanggan</p>
                            @endif
                        </div>
                    </div>
                    @if(!auth('customer')->check())
                    <a href="{{ route('report.transactions.pdf') }}" class="btn btn-light">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-custom">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-custom">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0 text-white">
                            <i class="bi bi-list-check me-2"></i>
                            {{ auth('customer')->check() ? 'Transaksi Saya' : 'Daftar Transaksi' }}
                        </h3>
                        <small class="text-white opacity-75">Total {{ $transactions->total() }} transaksi ditemukan</small>
                    </div>
                    @if(!auth('customer')->check())
                    <a href="{{ route('admin.transactions.offline.create') }}" class="btn btn-light">
                        <i class="bi bi-cash-coin me-2"></i>Transaksi Offline
                    </a>
                    @endif
                </div>
                
                <div class="card-body p-0">
                    @if($transactions->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-receipt" style="font-size: 3rem; color: var(--gray);"></i>
                            @if(auth('customer')->check())
                            <h4 class="mt-3 text-muted">Belum ada transaksi</h4>
                            <p class="text-muted">Mulai dengan membuat transaksi pertama Anda</p>
                            <a href="{{ route('transactions.create') }}" class="btn btn-primary-custom mt-2">
                                <i class="bi bi-cart-plus me-2"></i>Buat Transaksi
                            </a>
                            @else
                            <h4 class="mt-3 text-muted">Belum ada data transaksi</h4>
                            <p class="text-muted">Tidak ada transaksi yang tercatat</p>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        @if(!auth('customer')->check())
                                            <th width="20%">Pelanggan</th>
                                        @endif
                                        <th width="15%">Tanggal</th>
                                        <th width="15%">Status</th>
                                        <th width="15%">Total</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $trx)
                                    <tr>
                                        <td class="fw-bold text-primary">
                                            {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                                        </td>
                                        @if(!auth('customer')->check())
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($trx->customer)
                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 36px; height: 36px;">
                                                        <i class="bi bi-person text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="d-block">{{ $trx->customer->name }}</strong>
                                                        <small class="text-muted">{{ $trx->customer->email }}</small>
                                                    </div>
                                                    @elseif(Str::startsWith($trx->notes, 'Offline customer:'))
                                                    <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 36px; height: 36px;">
                                                        <i class="bi bi-shop text-secondary"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="d-block">{{ trim(Str::replace('Offline customer:', '', $trx->notes)) }}</strong>
                                                        <small class="text-muted">Customer Offline</small>
                                                    </div>
                                                    @else
                                                    <div class="bg-light rounded px-3 py-2">
                                                        <small class="text-muted">-</small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            @php
                                                $date = is_string($trx->date) ? \Carbon\Carbon::parse($trx->date) : $trx->date;
                                            @endphp
                                            <div class="text-center">
                                                <div class="fw-bold">{{ $date->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $date->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($trx->customer_id === null)
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i>Selesai
                                                </span>
                                            @elseif($trx->status === 'pending')
                                                <span class="badge bg-warning rounded-pill px-3 py-2">
                                                    <i class="bi bi-clock me-1"></i>Menunggu
                                                </span>
                                            @elseif($trx->status === 'verified')
                                                <span class="badge bg-info rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i>Terverifikasi
                                                </span>
                                            @elseif($trx->status === 'shipped')
                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                    <i class="bi bi-truck me-1"></i>Dikirim
                                                </span>
                                            @elseif($trx->status === 'completed')
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle me-1"></i>Selesai
                                                </span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-3 py-2">
                                                    {{ $trx->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-primary">
                                            Rp {{ number_format((float)$trx->total, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                @if(auth('web')->check())
                                                    <a href="{{ route('admin.transactions.show', $trx->id) }}" 
                                                       class="btn btn-sm btn-outline-info d-flex align-items-center" 
                                                       title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>Lihat
                                                    </a>
                                                    <a href="{{ route('admin.transactions.nota', $trx->id) }}" 
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-success d-flex align-items-center" 
                                                       title="Cetak Nota">
                                                        <i class="bi bi-printer"></i>Nota
                                                    </a>
                                                @else
                                                    <a href="{{ route('transactions.show', $trx->id) }}" 
                                                       class="btn btn-sm btn-outline-info d-flex align-items-center" 
                                                       title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('transactions.nota', $trx->id) }}" 
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-success d-flex align-items-center" 
                                                       title="Cetak Nota">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                    @if($trx->payment_method === 'qris' && $trx->status === 'pending' && !$trx->payment_proof)
                                                        <a href="{{ route('transactions.show', $trx->id) }}" 
                                                           class="btn btn-sm btn-outline-warning d-flex align-items-center" 
                                                           title="Bayar">
                                                            <i class="bi bi-credit-card"></i>
                                                        </a>
                                                    @endif
                                                    @if($trx->status === 'shipped')
                                                        <form action="{{ route('transactions.update', $trx->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="action" value="complete">
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-outline-success d-flex align-items-center"
                                                                    title="Konfirmasi Diterima"
                                                                    onclick="return confirm('Konfirmasi pesanan sudah diterima?')">
                                                                <i class="bi bi-check-lg"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                                @if(auth('web')->check())
                                                    <form action="{{ route('admin.transactions.destroy', $trx->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger d-flex align-items-center"
                                                                title="Hapus"
                                                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                
                @if($transactions->hasPages())
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                            </small>
                        </div>
                        <div>
                            {{ $transactions->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
                @endif
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
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .btn-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
</style>
@endsection