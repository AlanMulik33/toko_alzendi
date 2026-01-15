@extends('layouts.app')

@section('title', 'Kelola Alamat')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Alamat Saya</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="mb-3">
                <a href="{{ route('customer.addresses.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Alamat Baru
                </a>
            </div>

            @forelse($addresses as $address)
                <div class="card mb-3 @if($address->is_default) border-primary @endif">
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col">
                                <h5 class="card-title">
                                    @if($address->label)
                                        {{ $address->label }}
                                    @else
                                        Alamat
                                    @endif
                                    @if($address->is_default)
                                        <span class="badge bg-primary">Default</span>
                                    @endif
                                </h5>
                                <p class="card-text">{{ $address->address }}</p>
                                @if($address->phone)
                                    <p class="card-text text-muted">
                                        <i class="bi bi-telephone"></i> {{ $address->phone }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-auto">
                                @if(!$address->is_default)
                                    <form action="{{ route('customer.addresses.setDefault', $address) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary" 
                                                onclick="return confirm('Jadikan alamat ini default?')">
                                            Set Default
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('customer.addresses.edit', $address) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin hapus alamat ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    Belum ada alamat. <a href="{{ route('customer.addresses.create') }}">Tambah alamat pertama Anda</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
