@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
<div class="card card-custom shadow-soft">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <h2 class="mb-0"><i class="bi bi-tags me-2"></i>Daftar Kategori</h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary-custom">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
        </a>
    </div>
    
    <div class="card-body p-0">
        @if($categories->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-tags" style="font-size: 3rem; color: var(--gray);"></i>
                <h4 class="mt-3 text-muted">Belum ada kategori</h4>
                <p class="text-muted">Mulai dengan menambahkan kategori pertama Anda</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary-custom mt-2">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Kategori Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Kategori</th>
                            <th width="200" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td class="fw-bold text-primary">{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-tag text-primary"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $cat->name }}</strong>
                                        @if($cat->description)
                                            <small class="text-muted">{{ Str::limit($cat->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('categories.show', $cat->id) }}" 
                                       class="btn btn-sm btn-outline-info d-flex align-items-center" 
                                       title="Lihat Detail">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </a>
                                    <a href="{{ route('categories.edit', $cat->id) }}" 
                                       class="btn btn-sm btn-outline-warning d-flex align-items-center" 
                                       title="Edit">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger d-flex align-items-center" 
                                                title="Hapus"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
    
    @if($categories->hasPages())
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-center">
            {{ $categories->links('pagination::bootstrap-5') }}
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