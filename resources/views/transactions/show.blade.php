@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="row">
    <div class="col-md-10">
        <h2>Detail Transaksi #{{ $transaction->id }}</h2>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Pelanggan:</strong> {{ $transaction->customer->name }}</p>
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

                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                </form>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
