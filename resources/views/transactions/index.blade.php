@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<h2>Daftar Transaksi</h2>
<a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>
<a href="{{ route('report.transactions.pdf') }}" class="btn btn-success mb-3">Cetak PDF</a>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Pelanggan</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $trx)
        <tr>
            <td>{{ $trx->id }}</td>
            <td>{{ $trx->customer->name }}</td>
            <td>{{ $trx->date->format('d-m-Y H:i') }}</td>
            <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
            <td>
                <a href="{{ route('transactions.show', $trx->id) }}" class="btn btn-sm btn-info">Lihat</a>
                <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Tidak ada data transaksi</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $transactions->links() }}
@endsection
