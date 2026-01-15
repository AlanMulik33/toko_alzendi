@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="row">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="col-md-10">
        <h2>Detail Transaksi #{{ $transaction->id }}</h2>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Pelanggan:</strong>
                            @if($transaction->customer)
                                {{ $transaction->customer->name }}
                            @elseif(Str::startsWith($transaction->notes, 'Offline customer:'))
                                {{ trim(Str::replace('Offline customer:', '', $transaction->notes)) }}
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>Tanggal:</strong> {{ is_string($transaction->date) ? \Carbon\Carbon::parse($transaction->date)->format('d-m-Y H:i:s') : $transaction->date->format('d-m-Y H:i:s') }}</p>
                    </div>
                </div>

                <h4>Item Transaksi</h4>
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp {{ number_format($detail->price * $detail->qty, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="alert alert-info">
                    <strong>Total: Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong>
                </div>

                @if(auth('web')->check())
                    @if($transaction->status === 'pending')
                        <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="verify">
                            <button type="submit" class="btn btn-warning">Verifikasi Pembayaran</button>
                        </form>
                    @elseif($transaction->status === 'verified')
                        <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="action" value="ship">
                            <button type="submit" class="btn btn-primary">Kirim Pesanan</button>
                        </form>
                    @endif
                @endif
                @if(auth('customer')->check() && $transaction->status === 'shipped' && $transaction->customer_id == auth('customer')->id())
                    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="complete">
                        <button type="submit" class="btn btn-success">Konfirmasi Pesanan Diterima</button>
                    </form>
                @endif
                @if(auth('web')->check())
                    <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Kembali</a>
                @else
                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
