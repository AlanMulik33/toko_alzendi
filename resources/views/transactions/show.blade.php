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

                @if($transaction->payment_method === 'qris')
                    <div class="mb-3">
                        <h5>QRIS Pembayaran</h5>
                        @if($transaction->qris_code)
                            <img src="{{ $transaction->qris_code }}" alt="QRIS Code" style="width:220px;height:220px;object-fit:contain;border:1px solid #ccc;padding:8px;background:#fff;">
                        @else
                            <span class="text-danger">QRIS tidak tersedia</span>
                        @endif
                        <div class="mt-2">
                            <strong>Status Pembayaran: </strong>
                            @if($transaction->status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                            @elseif($transaction->status === 'verified')
                                <span class="badge bg-success">Pembayaran Berhasil</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                            @endif
                        </div>
                        @if(auth('customer')->check() && $transaction->status === 'pending' && $transaction->customer_id == auth('customer')->id())
                            <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="pay">
                                <div class="mb-2">
                                    <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
                                    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-success">Bayar</button>
                            </form>
                        @endif
                        @if($transaction->payment_proof)
                            <div class="mt-2">
                                <strong>Bukti Pembayaran:</strong><br>
                                <a href="{{ asset('storage/'.$transaction->payment_proof) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$transaction->payment_proof) }}" alt="Bukti Pembayaran" style="max-width:180px;max-height:180px;border:1px solid #ccc;">
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

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
