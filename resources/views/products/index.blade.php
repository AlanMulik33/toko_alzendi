@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="card card-custom shadow-soft">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0"><i class="bi bi-box-seam me-2"></i>Daftar Produk</h2>
            <small class="text-white opacity-75">Total {{ $products->total() }} produk ditemukan</small>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-light">
            <i class="bi bi-plus-lg me-2"></i>Tambah Produk
        </a>
    </div>
    
    <div class="card-body p-0">
        @if($products->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-box" style="font-size: 3rem; color: var(--gray);"></i>
                <h4 class="mt-3 text-muted">Belum ada produk</h4>
                <p class="text-muted">Mulai dengan menambahkan produk pertama Anda</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary-custom mt-2">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Produk Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Produk</th>
                            <th width="150">Kategori</th>
                            <th width="120">Harga</th>
                            <th width="100">Stok</th>
                            <th width="150" class="text-center">Status Stok</th>
                            <th width="200" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                        <tr>
                            <td class="fw-bold text-primary">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-box text-primary"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $p->name }}</strong>
                                        @if($p->description)
                                            <small class="text-muted">{{ Str::limit($p->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                    <i class="bi bi-tag me-1"></i>{{ $p->category->name }}
                                </span>
                            </td>
                            <td class="fw-bold text-primary">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td class="fw-bold">{{ $p->stock }}</td>
                            <td class="text-center">
                                @if($p->stock > 20)
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Tersedia
                                    </span>
                                @elseif($p->stock > 0)
                                    <span class="badge bg-warning rounded-pill px-3 py-2">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Terbatas
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>Habis
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('products.show', $p->id) }}" 
                                       class="btn btn-sm btn-outline-info d-flex align-items-center" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </a>
                                    <a href="{{ route('products.edit', $p->id) }}" 
                                       class="btn btn-sm btn-outline-warning d-flex align-items-center" 
                                       title="Edit">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('products.destroy', $p->id) }}" method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger d-flex align-items-center" 
                                                title="Hapus"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <i class="bi bi-trash me-1"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    
    @if($products->hasPages())
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                </small>
            </div>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif
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
    }
</style>
@endsection