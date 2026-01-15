@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container mt-5">
    <h1>Customer Dashboard</h1>
    <p>Welcome, {{ auth('customer')->user()->name }}!</p>
    <p>Email: {{ auth('customer')->user()->email }}</p>
    <p>Use the navigation menu above to view your transactions or create a new one.</p>

    <hr>
    <h3>Produk Tersedia per Kategori</h3>
    @if(isset($categories) && $categories->count())
        @foreach($categories as $category)
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    {{ $category->name }}
                </div>
                <div class="card-body p-0">
                    @if($category->products->count())
                        <ul class="list-group list-group-flush">
                            @foreach($category->products as $product)
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
    @else
        <p>Tidak ada kategori atau produk tersedia.</p>
    @endif
</div>
@endsection