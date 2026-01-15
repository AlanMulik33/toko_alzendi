@extends('layouts.app')

@section('title', 'Edit Alamat')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-custom shadow-soft border-0 overflow-hidden">
                <div class="card-header-custom d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-geo-alt text-white" style="font-size: 1.8rem;"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 text-white">Edit Alamat</h1>
                        <p class="mb-0 text-white opacity-75">
                            <i class="bi bi-pencil-square me-1"></i>Update informasi alamat pengiriman
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-custom shadow-soft border-0">
                <div class="card-header-custom">
                    <h3 class="mb-0 text-white"><i class="bi bi-geo-alt-fill me-2"></i>Edit Alamat</h3>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger alert-custom mb-4">
                        <div class="d-flex">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <strong class="d-block">Validasi Error</strong>
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    @php
                        $fromTransaction = request('from') === 'transaction';
                    @endphp

                    <form action="{{ route('customer.addresses.update', $address) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if($fromTransaction)
                        <div class="alert alert-info alert-custom mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            Anda sedang mengedit alamat dari halaman transaksi
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="label" class="form-label fw-bold">
                                <i class="bi bi-tag me-1"></i>Label Alamat
                                <small class="text-muted ms-1">(contoh: Rumah, Kantor, Toko)</small>
                            </label>
                            <input type="text" class="form-control form-control-custom @error('label') is-invalid @enderror" 
                                   id="label" name="label" value="{{ old('label', $address->label) }}" 
                                   placeholder="e.g., Rumah, Kantor, Apartemen">
                            @error('label')
                                <div class="invalid-feedback d-flex align-items-center mt-1">
                                    <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold">
                                <i class="bi bi-geo-alt me-1"></i>Alamat Lengkap <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control form-control-custom @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="4" required
                                      placeholder="Masukkan alamat lengkap (jalan, nomor, kota, provinsi, kode pos)">{{ old('address', $address->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback d-flex align-items-center mt-1">
                                    <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">
                                <i class="bi bi-telephone me-1"></i>Nomor Telepon
                            </label>
                            <input type="text" class="form-control form-control-custom @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $address->phone) }}" 
                                   placeholder="08xxxxxxxxxx (opsional)">
                            @error('phone')
                                <div class="invalid-feedback d-flex align-items-center mt-1">
                                    <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1"
                                       @checked(old('is_default', $address->is_default))>
                                <label class="form-check-label fw-bold" for="is_default">
                                    <i class="bi bi-star me-1"></i>Jadikan alamat default
                                </label>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle me-1"></i>Alamat default akan digunakan sebagai alamat pengiriman utama
                            </small>
                        </div>

                        <div class="d-flex gap-3 mt-4 pt-4 border-top">
                            <button type="submit" class="btn btn-primary-custom flex-fill">
                                <i class="bi bi-check-circle me-2"></i>Update Alamat
                            </button>
                            @if($fromTransaction)
                                <a href="{{ route('transactions.create') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Transaksi
                                </a>
                            @else
                                <a href="{{ route('customer.addresses.index') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-x-circle me-2"></i>Batal
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
</style>
@endsection