<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-shop me-2"></i>Toko Alzendi
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth('web') <!-- Admin -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" 
                           href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam me-1"></i>Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" 
                           href="{{ route('categories.index') }}">
                            <i class="bi bi-tags me-1"></i>Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/transactions*') ? 'active' : '' }}" 
                           href="{{ route('admin.transactions.index') }}">
                            <i class="bi bi-receipt me-1"></i>Transaksi
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarReports" role="button" 
                           data-bs-toggle="dropdown">
                            <i class="bi bi-bar-chart me-1"></i>Laporan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarReports">
                            <li><a class="dropdown-item" href="{{ route('report.dashboard') }}">
                                <i class="bi bi-speedometer me-2"></i>Dashboard Laporan
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('report.transactions.pdf') }}">
                                <i class="bi bi-file-pdf me-2"></i>Export PDF
                            </a></li>
                        </ul>
                    </li>
                @endauth
                @auth('customer') <!-- Customer -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/dashboard*') ? 'active' : '' }}" 
                           href="{{ route('customer.dashboard') }}">
                            <i class="bi bi-house-door me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('transactions') && !Request::is('transactions/create') ? 'active' : '' }}" 
                           href="{{ route('transactions.index') }}">
                            <i class="bi bi-list-check me-1"></i>Transaksi Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('transactions/create') ? 'active' : '' }}" 
                           href="{{ route('transactions.create') }}">
                            <i class="bi bi-cart-plus me-1"></i>Transaksi Baru
                        </a>
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/login*') ? 'active' : '' }}" 
                           href="{{ route('customer.login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login Customer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/register*') ? 'active' : '' }}" 
                           href="{{ route('customer.register') }}">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/login*') ? 'active' : '' }}" 
                           href="{{ route('admin.login') }}">
                            <i class="bi bi-shield-lock me-1"></i>Admin Login
                        </a>
                    </li>
                @endguest
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item">
                        @if(auth('web')->check())
                            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout Admin
                                </button>
                            </form>
                        @elseif(auth('customer')->check())
                            <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout Customer
                                </button>
                            </form>
                        @endif
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>