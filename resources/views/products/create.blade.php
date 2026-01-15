@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-custom shadow-soft">
            <div class="card-header-custom">
                <h2 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</h2>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-box me-1"></i>Nama Produk
                                </label>
                                <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Masukkan nama produk" required>
                                @error('name')
                                    <div class="invalid-feedback d-flex align-items-center mt-1">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="bi bi-text-paragraph me-1"></i>Deskripsi Produk
                                </label>
                                <textarea class="form-control form-control-custom" id="description" name="description" 
                                          rows="5" placeholder="Masukkan deskripsi produk (opsional)">{{ old('description') }}</textarea>
                                <small class="text-muted mt-1 d-block">
                                    <i class="bi bi-info-circle me-1"></i>Deskripsi akan membantu pelanggan memahami produk dengan lebih baik.
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-bold">
                                    <i class="bi bi-tag me-1"></i>Kategori
                                </label>
                                <select class="form-select form-control-custom @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback d-flex align-items-center mt-1">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                                @if($categories->isEmpty())
                                <small class="text-danger mt-1 d-block">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Belum ada kategori. 
                                    <a href="{{ route('categories.create') }}" class="text-decoration-none">Buat kategori terlebih dahulu</a>
                                </small>
                                @endif
                            </div>
                            
                            <div class="mb-4">
                                <label for="price" class="form-label fw-bold">
                                    <i class="bi bi-currency-dollar me-1"></i>Harga
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" class="form-control form-control-custom @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" 
                                           step="100" min="0" placeholder="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback d-flex align-items-center mt-1">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="stock" class="form-label fw-bold">
                                    <i class="bi bi-box-seam me-1"></i>Stok
                                </label>
                                <input type="number" class="form-control form-control-custom @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock') }}" 
                                       min="0" placeholder="0" required>
                                @error('stock')
                                    <div class="invalid-feedback d-flex align-items-center mt-1">
                                        <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted mt-1 d-block">
                                    <i class="bi bi-info-circle me-1"></i>Masukkan jumlah stok yang tersedia.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 mt-4 pt-4 border-top">
                        <button type="submit" class="btn btn-primary-custom flex-fill">
                            <i class="bi bi-check-circle me-2"></i>Simpan Produk
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary flex-fill">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection