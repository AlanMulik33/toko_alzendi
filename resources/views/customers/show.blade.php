@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2>{{ $customer->name }}</h2>
        <div class="card">
            <div class="card-body">
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Telepon:</strong> {{ $customer->phone }}</p>
                <p><strong>Alamat:</strong> {{ $customer->address }}</p>
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
