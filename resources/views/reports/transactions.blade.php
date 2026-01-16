<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #000; padding: 8px; text-align: left; }
        table th { background-color: #f0f0f0; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Transaksi</h1>
        <p>Tanggal: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ $trx->id }}</td>
                <td>
                    @if($trx->customer)
                        {{ $trx->customer->name }}
                    @else
                        {{ $trx->notes ?? 'Offline' }}
                    @endif
                </td>
                <td>{{ $trx->date->format('d-m-Y H:i') }}</td>
                <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="3">TOTAL KESELURUHAN</td>
                <td>Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
