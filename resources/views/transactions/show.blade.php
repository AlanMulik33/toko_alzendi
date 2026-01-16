@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-receipt-cutoff text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 text-white">Detail Transaksi #{{ $transaction->id }}</h1>
                        <p class="mb-0 text-white opacity-75">
                            @php
                                $date = is_string($transaction->date) ? \Carbon\Carbon::parse($transaction->date) : $transaction->date;
                            @endphp
                            <i class="bi bi-calendar3 me-1"></i>{{ $date->format('d/m/Y H:i:s') }}
                        </p>
                    </div>
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

    <div class="row">
        <div class="col-md-8">
            <!-- Customer Info -->
            <div class="card card-custom shadow-soft border-0 mb-4">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-person-circle me-2"></i>Informasi Pelanggan</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div>
                                    <strong class="d-block">
                                        @if($transaction->customer)
                                            {{ $transaction->customer->name }}
                                        @elseif(!empty($transaction->notes) && Str::startsWith($transaction->notes, 'Offline customer:'))
                                            {{ trim(Str::replace('Offline customer:', '', $transaction->notes)) }}
                                            <span class="badge bg-secondary ms-2">Offline</span>
                                        @else
                                            <span class="text-muted">Customer Offline</span>
                                        @endif
                                    </strong>
                                    @if($transaction->customer)
                                        <small class="text-muted">{{ $transaction->customer->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <div class="fw-bold text-muted mb-2">Status Transaksi</div>
                                @if($transaction->customer_id === null)
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    </span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge bg-warning rounded-pill px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>Menunggu
                                    </span>
                                @elseif($transaction->status === 'verified')
                                    <span class="badge bg-info rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Terverifikasi
                                    </span>
                                @elseif($transaction->status === 'shipped')
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="bi bi-truck me-1"></i>Dikirim
                                    </span>
                                @elseif($transaction->status === 'completed')
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        {{ $transaction->status }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Items -->
            <div class="card card-custom shadow-soft border-0 mb-4">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-cart me-2"></i>Detail Produk</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga Satuan</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->details as $detail)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-box text-primary"></i>
                                            </div>
                                            <div>
                                                @if($detail->product)
                                                    <strong class="d-block">{{ $detail->product->name }}</strong>
                                                    @if($detail->product->sku)
                                                        <small class="text-muted">SKU: {{ $detail->product->sku }}</small>
                                                    @endif
                                                @else
                                                    <strong class="d-block text-danger">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>[Produk dihapus]
                                                    </strong>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold">
                                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            {{ $detail->qty }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        Rp {{ number_format($detail->price * $detail->qty, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fs-5">Total Transaksi:</div>
                        <div class="fs-4 fw-bold text-primary">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if($transaction->payment_method === 'qris')
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-qr-code me-2"></i>Informasi Pembayaran QRIS</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="fw-bold text-muted mb-2">Status Pembayaran</div>
                                @if($transaction->status === 'pending')
                                    <span class="badge bg-warning rounded-pill px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>Menunggu Pembayaran
                                    </span>
                                @elseif($transaction->status === 'verified')
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Pembayaran Berhasil
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($transaction->qris_code)
                            <div class="mb-4">
                                <div class="fw-bold text-muted mb-2">Kode QRIS</div>
                                <div class="border rounded p-3 bg-white">
                                    <img src="{{ asset('qris.jpg') }}" alt="QRIS Code" 
                                         style="width: 200px; height: 200px; object-fit: contain;">
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            @if(auth('customer')->check() && $transaction->status === 'pending' && $transaction->customer_id == auth('customer')->id())
                            <div class="bg-light rounded p-4 border">
                                <h5 class="fw-bold mb-3">Upload Bukti Pembayaran</h5>
                                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="pay">
                                    
                                    <div class="mb-3">
                                        <label for="payment_proof" class="form-label fw-bold">
                                            <i class="bi bi-upload me-1"></i>Pilih File Bukti
                                        </label>
                                        <input type="file" name="payment_proof" id="payment_proof" 
                                               class="form-control form-control-custom" 
                                               accept="image/*" required>
                                        <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-credit-card me-2"></i>Kirim Bukti Pembayaran
                                    </button>
                                </form>
                            </div>
                            @endif
                            
                            @if($transaction->payment_proof)
                                <div class="mt-4">
                                    <div class="fw-bold text-muted mb-2">Bukti Pembayaran</div>
                                    <div class="border rounded p-3 bg-white">
                                        <a href="{{ $transaction->payment_proof }}" target="_blank">
                                            <img src="{{ $transaction->payment_proof }}"
                                                alt="Bukti Pembayaran" 
                                                style="max-width: 100%; max-height: 200px; object-fit: contain;">
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <!-- Action Buttons -->
            <div class="card card-custom shadow-soft border-0 mb-4">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-gear me-2"></i>Aksi</h3>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        @if(auth('web')->check())
                            <a href="{{ route('admin.transactions.nota', $transaction->id) }}" 
                               target="_blank" class="btn btn-outline-success">
                                <i class="bi bi-printer me-2"></i>Cetak Nota
                            </a>
                            
                            @if($transaction->status === 'pending')
                                <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="verify">
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="bi bi-check-circle me-2"></i>Verifikasi Pembayaran
                                    </button>
                                </form>
                            @elseif($transaction->status === 'verified')
                                <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="ship">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-truck me-2"></i>Kirim Pesanan
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                    <i class="bi bi-trash me-2"></i>Hapus Transaksi
                                </button>
                            </form>
                            
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        @else
                            <a href="{{ route('transactions.nota', $transaction->id) }}" 
                               target="_blank" class="btn btn-outline-success">
                                <i class="bi bi-printer me-2"></i>Cetak Nota
                            </a>
                            
                            @if($transaction->status === 'shipped' && $transaction->customer_id == auth('customer')->id())
                                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="complete">
                                    <button type="submit" class="btn btn-success w-100"
                                            onclick="return confirm('Konfirmasi pesanan sudah diterima?')">
                                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Diterima
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                    <i class="bi bi-trash me-2"></i>Hapus Transaksi
                                </button>
                            </form>
                            
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Payment Method -->
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-credit-card me-2"></i>Metode Pembayaran</h3>
                </div>
                <div class="card-body p-4 text-center">
                    @if($transaction->payment_method === 'cash')
                        <div class="mb-3">
                            <i class="bi bi-cash-coin" style="font-size: 3rem; color: var(--success);"></i>
                        </div>
                        <h4 class="fw-bold">Cash / Tunai</h4>
                    @else
                        <div class="mb-3">
                            <i class="bi bi-qr-code" style="font-size: 3rem; color: var(--primary);"></i>
                        </div>
                        <h4 class="fw-bold">QRIS</h4>
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
    
    .badge.rounded-pill {
        padding: 0.5rem 1rem;
    }
    
    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection