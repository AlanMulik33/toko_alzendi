<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Toko Retail</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth('web') <!-- Admin -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Manage Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">Manage Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.index') }}">View Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('report.transactions.pdf') }}">Report PDF</a>
                    </li>
                @endauth
                @auth('customer') <!-- Customer -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.dashboard') }}">Dashboard Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.index') }}">My Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.create') }}">New Transaction</a>
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.login') }}">Customer Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.register') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.login') }}">Admin Login</a>
                    </li>
                @endguest
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item">
                        @if(auth('web')->check())
                            <a class="nav-link" href="{{ route('admin.logout') }}">Logout Admin</a>
                        @elseif(auth('customer')->check())
                            <a class="nav-link" href="{{ route('customer.logout') }}">Logout Customer</a>
                        @endif
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
