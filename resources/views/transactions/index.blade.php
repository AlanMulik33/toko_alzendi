@extends('layouts.app')

@section('title', auth('customer')->check() ? 'My Transactions' : 'Daftar Transaksi')

@section('content')
@if(auth('customer')->check())
    <h2>My Transactions</h2>
    <p>Riwayat transaksi Anda</p>
@else
    <h2>Daftar Transaksi</h2>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>
    <a href="{{ route('report.transactions.pdf') }}" class="btn btn-success mb-3">Cetak PDF</a>
@endif

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            @if(!auth('customer')->check())
                <th>Pelanggan</th>
            @endif
            <th>Tanggal</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $trx)
        <tr>
            <td>{{ $trx->id }}</td>
            @if(!auth('customer')->check())
                <td>{{ $trx->customer->name }}</td>
            @endif
            <td>{{ is_string($trx->date) ? \Carbon\Carbon::parse($trx->date)->format('d-m-Y H:i') : $trx->date->format('d-m-Y H:i') }}</td>
            <td>Rp {{ number_format((float)$trx->total, 0, ',', '.') }}</td>
            <td>
                @if(auth('web')->check())
                    <a href="{{ route('admin.transactions.show', $trx->id) }}" class="btn btn-sm btn-info">Lihat</a>
                @endif
                @if($trx->customer_id == auth('customer')->id() || auth('web')->check())
                    <a href="{{ route('transactions.nota', $trx->id) }}" class="btn btn-sm btn-success">Nota</a>
                @endif
                @if(auth('web')->check())
                    <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ auth('customer')->check() ? '4' : '5' }}" class="text-center">
                @if(auth('customer')->check())
                    Belum ada transaksi
                @else
                    Tidak ada data transaksi
                @endif
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $transactions->links() }}
@endsection
