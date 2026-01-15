@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-custom shadow-soft">
            <div class="card-header-custom d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                     style="width: 50px; height: 50px;">
                    <i class="bi bi-box text-white" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h2 class="mb-0">{{ $product->name }}</h2>
                    <small class="text-white opacity-75">Detail Produk</small>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>Informasi Produk
                            </h5>
                            <div class="bg-light rounded p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Nama Produk</label>
                                        <p class="fs-5 fw-semibold">{{ $product->name }}</p>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Kategori</label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary bg-opacity-20 text-white px-3 py-2 rounded-pill">
                                                <i class="bi bi-tag me-1"></i>{{ $product->category->name }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Harga</label>
                                        <p class="fs-4 fw-bold text-primary">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-muted">Stok Tersedia</label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge @if($product->stock > 20) bg-success 
                                                             @elseif($product->stock > 0) bg-warning 
                                                             @else bg-danger @endif 
                                                    rounded-pill px-3 py-2 fs-6">
                                                <i class="bi bi-box-seam me-1"></i>{{ $product->stock }} Unit
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($product->description)
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Deskripsi</label>
                                        <div class="border-start border-3 border-primary ps-3 py-2 bg-white rounded">
                                            <p class="mb-0">{{ $product->description }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-4">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="bi bi-clock-history me-2"></i>Informasi Tambahan
                            </h5>
                            <div class="bg-light rounded p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Dibuat Pada</label>
                                    <p class="mb-0">
                                        <i class="bi bi-calendar-plus text-primary me-2"></i>
                                        {{ $product->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Terakhir Diupdate</label>
                                    <p class="mb-0">
                                        <i class="bi bi-calendar-check text-primary me-2"></i>
                                        {{ $product->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                
                                <div class="mt-4 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        ID Produk: <code>{{ $product->id }}</code>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square me-2"></i>Edit Produk
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                    <i class="bi bi-trash me-2"></i>Hapus Produk
                                </button>
                            </form>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge.rounded-pill {
        padding: 0.5rem 1.25rem;
    }
    
    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection