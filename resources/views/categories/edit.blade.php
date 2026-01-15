@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom shadow-soft">
            <div class="card-header-custom">
                <h2 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Kategori</h2>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">
                            <i class="bi bi-tag me-1"></i>Nama Kategori
                        </label>
                        <input type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" 
                               placeholder="Masukkan nama kategori" required>
                        @error('name')
                            <div class="invalid-feedback d-flex align-items-center mt-1">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">
                            <i class="bi bi-text-paragraph me-1"></i>Deskripsi
                        </label>
                        <textarea class="form-control form-control-custom" id="description" name="description" 
                                  rows="4" placeholder="Masukkan deskripsi kategori">{{ old('description', $category->description) }}</textarea>
                    </div>
                    
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary-custom flex-fill">
                            <i class="bi bi-check-circle me-2"></i>Update Kategori
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary flex-fill">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection