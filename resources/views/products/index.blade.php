@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<h2>Daftar Produk</h2>
<a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $p)
        <tr>
            <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->category->name }}</td>
            <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
            <td>{{ $p->stock }}</td>
            <td>
                <a href="{{ route('products.show', $p->id) }}" class="btn btn-sm btn-info">Lihat</a>
                <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('products.destroy', $p->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data produk</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $products->links() }}
@endsection

