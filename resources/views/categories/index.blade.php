@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
<h2>Daftar Kategori</h2>
<a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $cat)
        <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->name }}</td>
            <td>
                <a href="{{ route('categories.show', $cat->id) }}" class="btn btn-sm btn-info">Lihat</a>
                <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center">Tidak ada data kategori</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $categories->links() }}
@endsection
