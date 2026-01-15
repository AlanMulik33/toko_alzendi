<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi #{{ $transaction->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .nota-container {
            background-color: white;
            max-width: 400px;
            margin: 20px auto;
            padding: 30px;
            border: 2px solid #333;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .nota-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .nota-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .nota-subtitle {
            font-size: 12px;
            color: #666;
        }
        .nota-info {
            font-size: 12px;
            margin-bottom: 20px;
            border-bottom: 1px dashed #999;
            padding-bottom: 15px;
        }
        .nota-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .nota-info-label {
            font-weight: bold;
        }
        .nota-items {
            margin-bottom: 20px;
            border-bottom: 1px dashed #999;
            padding-bottom: 15px;
        }
        .nota-item {
            font-size: 11px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .nota-item-name {
            font-weight: bold;
            flex-basis: 100%;
            margin-bottom: 2px;
        }
        .nota-item-qty {
            color: #666;
            font-size: 10px;
        }
        .nota-item-subtotal {
            font-weight: bold;
        }
        .nota-total {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .nota-payment {
            font-size: 12px;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .nota-payment-label {
            font-weight: bold;
        }
        .nota-payment-method {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }
        .nota-footer {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 20px;
            border-top: 1px dashed #999;
            padding-top: 15px;
        }
        .nota-datetime {
            font-size: 10px;
            color: #666;
            text-align: center;
            margin-bottom: 10px;
        }
        .action-buttons {
            text-align: center;
            margin-top: 30px;
            print-hidden: true;
        }
        .btn-print {
            padding: 10px 20px;
            margin-right: 10px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .nota-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
            }
            .action-buttons {
                display: none;
            }
            .btn {
                display: none;
            }
        }

        .payment-method-icon {
            font-size: 24px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="nota-container">
        <!-- Header -->
        <div class="nota-header">
            <div class="nota-title">TOKO ALZENDI</div>
            <div class="nota-subtitle">Toko Retail</div>
        </div>

        <!-- Nomor & Waktu -->
        <div class="nota-datetime">
            {{ $transaction->date->format('d/m/Y H:i') }} WIB
        </div>

        <!-- Info Transaksi -->
        <div class="nota-info">
            <div class="nota-info-row">
                <span class="nota-info-label">No. Nota:</span>
                <span>#{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="nota-info-row">
                <span class="nota-info-label">Pelanggan:</span>
                <span>{{ $transaction->customer->name }}</span>
            </div>
            <div class="nota-info-row">
                <span class="nota-info-label">Kontak:</span>
                <span>{{ $transaction->customer->phone ?? '-' }}</span>
            </div>
        </div>

        <!-- Daftar Item -->
        <div class="nota-items">
            @foreach($transaction->details as $detail)
                <div class="nota-item">
                    <span class="nota-item-name">{{ $detail->product->name }}</span>
                    <span class="nota-item-qty">
                        {{ $detail->qty }} × Rp {{ number_format($detail->price, 0, ',', '.') }}
                    </span>
                    <span class="nota-item-subtotal">
                        = Rp {{ number_format($detail->qty * $detail->price, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="nota-total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
        </div>

        <!-- Metode Pembayaran -->
        <div class="nota-payment">
            <div class="nota-payment-label">Metode Pembayaran:</div>
            <div class="nota-payment-method">
                @if($transaction->payment_method === 'cash')
                    <div class="payment-method-icon">💵</div>
                    CASH (TUNAI)
                @else
                    <div class="payment-method-icon">📱</div>
                    QRIS
                @endif
            </div>
        </div>

        <!-- QRIS Code (jika QRIS) -->
        @if($transaction->payment_method === 'qris' && $transaction->qris_code)
            <div style="text-align: center; margin-bottom: 20px; padding: 15px; background-color: #f9f9f9; border-radius: 4px;">
                <div style="font-size: 11px; font-weight: bold; margin-bottom: 10px;">SCAN UNTUK PEMBAYARAN:</div>
                <img src="{{ $transaction->qris_code }}" alt="QRIS Code" style="width: 200px; height: 200px; border: 1px solid #ddd; padding: 5px; background-color: white;">
                <div style="font-size: 10px; color: #666; margin-top: 8px;">Nominal: Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="nota-footer">
            <div>Terima kasih atas pembelian Anda!</div>
            <div style="margin-top: 5px;">Barang yang sudah dibeli tidak dapat dikembalikan</div>
            <div style="margin-top: 10px; font-size: 9px;">
                Toko Alzendi - Bali
            </div>
        </div>
    </div>

    <!-- Action Buttons (tidak akan di-print) -->
    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary btn-print">🖨️ Cetak Nota</button>
        <a href="{{ auth('web')->check() ? route('admin.transactions.index') : route('transactions.index') }}" class="btn btn-secondary btn-print">← Kembali ke Daftar</a>
    </div>

    <script>
        // Auto print jika parameter print=1
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            window.print();
        }
    </script>
</body>
</html>
