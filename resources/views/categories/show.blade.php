@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom shadow-soft">
            <div class="card-header-custom d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                     style="width: 50px; height: 50px;">
                    <i class="bi bi-tag text-white" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h2 class="mb-0">{{ $category->name }}</h2>
                    <small class="text-white opacity-75">Detail Kategori</small>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-info-circle me-2"></i>Informasi Kategori
                    </h5>
                    <div class="bg-light rounded p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Nama Kategori</label>
                            <p class="fs-5 fw-semibold">{{ $category->name }}</p>
                        </div>
                        
                        <div>
                            <label class="form-label fw-bold text-muted">Deskripsi</label>
                            @if($category->description)
                                <div class="border-start border-3 border-primary ps-3 py-2">
                                    <p class="mb-0">{{ $category->description }}</p>
                                </div>
                            @else
                                <p class="text-muted fst-italic">
                                    <i class="bi bi-dash-circle me-1"></i>Tidak ada deskripsi
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if(isset($category->products) && $category->products->count() > 0)
                <div class="mb-4">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-box me-2"></i>Produk dalam Kategori
                        <span class="badge bg-primary ms-2">{{ $category->products->count() }}</span>
                    </h5>
                    <div class="list-group">
                        @foreach($category->products->take(5) as $product)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small class="text-muted">{{ $product->sku }}</small>
                                </div>
                                <span class="badge bg-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($category->products->count() > 5)
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-three-dots"></i>
                                {{ $category->products->count() - 5 }} produk lainnya
                            </small>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <div class="d-flex gap-3 mt-4 pt-4 border-top">
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning flex-fill">
                        <i class="bi bi-pencil-square me-2"></i>Edit Kategori
                    </a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100" 
                                onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                            <i class="bi bi-trash me-2"></i>Hapus Kategori
                        </button>
                    </form>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary flex-fill">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item {
        border: 1px solid var(--gray-light);
        margin-bottom: 0.5rem;
        border-radius: 8px !important;
        transition: all 0.2s;
    }
    
    .list-group-item:hover {
        border-color: var(--primary);
        transform: translateX(5px);
    }
</style>
@endsection