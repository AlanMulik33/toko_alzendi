@extends('layouts.app')

@section('title', auth('customer')->check() ? 'My Transactions' : 'Daftar Transaksi')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(auth('customer')->check())
    <h2>My Transactions</h2>
    <p>Riwayat transaksi Anda</p>
@else
    <h2>Daftar Transaksi</h2>
    <a href="{{ route('report.transactions.pdf') }}" class="btn btn-success mb-3">Cetak PDF</a>
@endif

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            @if(!auth('customer')->check())
                <th>Pelanggan</th>
            @endif
            <th>Tanggal</th>
            <th>Status</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $trx)
        <tr>
            <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
            @if(!auth('customer')->check())
                <td>
                    @if($trx->customer)
                        {{ $trx->customer->name }}
                    @elseif(Str::startsWith($trx->notes, 'Offline customer:'))
                        {{ trim(Str::replace('Offline customer:', '', $trx->notes)) }}
                    @else
                        -
                    @endif
                </td>
            @endif
            <td>{{ is_string($trx->date) ? \Carbon\Carbon::parse($trx->date)->format('d-m-Y H:i') : $trx->date->format('d-m-Y H:i') }}</td>
            <td>
                @if($trx->customer_id === null)
                    <span class="badge bg-success">Selesai</span>
                @elseif($trx->status === 'pending')
                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                @elseif($trx->status === 'verified')
                    <span class="badge bg-info text-dark">Terverifikasi</span>
                @elseif($trx->status === 'shipped')
                    <span class="badge bg-primary">Dikirim</span>
                @elseif($trx->status === 'completed')
                    <span class="badge bg-success">Selesai</span>
                @else
                    <span class="badge bg-secondary">{{ $trx->status }}</span>
                @endif
            </td>
            <td>Rp {{ number_format((float)$trx->total, 0, ',', '.') }}</td>
            <td>
                @if(auth('web')->check())
                    <a href="{{ route('admin.transactions.show', $trx->id) }}" class="btn btn-sm btn-info">Lihat</a>
                @endif
                @if($trx->customer_id == auth('customer')->id() || auth('web')->check())
                    @if(auth('web')->check())
                        <a href="{{ route('admin.transactions.nota', $trx->id) }}" class="btn btn-sm btn-success">Nota</a>
                    @else
                        <a href="{{ route('transactions.nota', $trx->id) }}" class="btn btn-sm btn-success">Nota</a>
                        @if($trx->payment_method === 'qris' && $trx->status === 'pending' && !$trx->payment_proof)
                            <a href="{{ route('transactions.show', $trx->id) }}" class="btn btn-sm btn-warning">Bayar</a>
                        @endif
                        @if($trx->status === 'shipped')
                            <form action="{{ route('transactions.update', $trx->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="complete">
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pesanan sudah diterima?')">Konfirmasi Diterima</button>
                            </form>
                        @endif
                    @endif
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
