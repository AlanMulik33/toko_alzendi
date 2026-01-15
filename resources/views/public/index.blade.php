@extends('layouts.app')

@section('title', 'Toko Alzendi - Publik')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Selamat Datang di Toko Alzendi</h2>
        <div>
            <a href="{{ route('customer.login') }}" class="btn btn-outline-primary me-2">Login</a>
            <a href="{{ route('customer.register') }}" class="btn btn-primary">Register</a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Transaksi</h5>
                    <p class="display-6">{{ $totalTransactions }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Produk Terjual</h5>
                    <p class="display-6">{{ $totalProductsSold }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Produk Terpopuler</h5>
                    <ul class="list-group">
                        @foreach($popularProducts as $pop)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $pop->name }}
                            <span class="badge bg-success">{{ $pop->sold_qty ?? 0 }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <h4 class="mb-3">Produk Berdasarkan Kategori</h4>
    @foreach($categories as $cat)
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                {{ $cat->name }}
            </div>
            <div class="card-body p-0">
                @if($cat->products->count())
                    <ul class="list-group list-group-flush">
                        @foreach($cat->products as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small>Stok: {{ $product->stock }} | Harga: Rp{{ number_format($product->price,0,',','.') }}</small>
                                @if($product->description)
                                    <br><small>{{ $product->description }}</small>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <div class="p-3">Tidak ada produk tersedia di kategori ini.</div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
