<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <p>Welcome, {{ auth()->user()->name }}!</p>
        <a href="{{ route('admin.logout') }}" class="btn btn-danger">Logout</a>
        <ul class="mt-3">
            <li><a href="{{ route('products.index') }}">Manage Products</a></li>
            <li><a href="{{ route('categories.index') }}">Manage Categories</a></li>
        </ul>
    </div>
</body>
</html>