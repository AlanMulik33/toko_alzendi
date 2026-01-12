<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Customer Dashboard</h1>
        <p>Welcome, {{ auth('customer')->user()->name }}!</p>
        <p>Email: {{ auth('customer')->user()->email }}</p>
        <a href="{{ route('customer.logout') }}" class="btn btn-danger">Logout</a>
        <hr>
        <h3>Available Products</h3>
        <!-- Tambahkan daftar produk di sini jika perlu -->
        <p>Here you can view products and make transactions.</p>
        <!-- Contoh link ke transaksi -->
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">Make a Transaction</a>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary ms-2">View My Transactions</a>
    </div>
</body>
</html>