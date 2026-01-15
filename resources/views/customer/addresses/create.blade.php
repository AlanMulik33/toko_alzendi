@extends('layouts.app')

@section('title', 'Tambah Alamat')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Tambah Alamat Baru</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.addresses.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="label" class="form-label">Label Alamat <small>(contoh: Rumah, Kantor, Toko)</small></label>
                            <input type="text" class="form-control @error('label') is-invalid @enderror" 
                                   id="label" name="label" value="{{ old('label') }}" placeholder="e.g., Rumah">
                            @error('label')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="4" required
                                      placeholder="Jln. ... No. ..., Kota, Provinsi, Kode Pos">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @php
                            $hasAddresses = auth('customer')->user()->addresses()->count() > 0;
                            $fromTransaction = request('from') === 'transaction';
                        @endphp

                        @if($hasAddresses)
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                                <label class="form-check-label" for="is_default">
                                    Jadikan alamat default
                                </label>
                            </div>
                        @else
                            <p class="text-muted small">Alamat ini akan dijadikan default karena ini adalah alamat pertama Anda.</p>
                        @endif

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                            @if($fromTransaction)
                                <a href="{{ route('transactions.create') }}" class="btn btn-secondary">← Kembali ke Transaksi</a>
                            @else
                                <a href="{{ route('customer.addresses.index') }}" class="btn btn-secondary">Batal</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
