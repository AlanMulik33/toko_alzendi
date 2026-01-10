@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
<h2>Daftar Pelanggan</h2>
<a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Tambah Pelanggan</a>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $cust)
        <tr>
            <td>{{ $cust->id }}</td>
            <td>{{ $cust->name }}</td>
            <td>{{ $cust->email }}</td>
            <td>{{ $cust->phone }}</td>
            <td>{{ $cust->address }}</td>
            <td>
                <a href="{{ route('customers.show', $cust->id) }}" class="btn btn-sm btn-info">Lihat</a>
                <a href="{{ route('customers.edit', $cust->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('customers.destroy', $cust->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data pelanggan</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $customers->links() }}
@endsection
