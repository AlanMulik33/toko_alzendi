<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Toko Alzendi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4ade80;
            --warning: #fbbf24;
            --danger: #ef4444;
            --gray-light: #e9ecef;
            --gray: #6c757d;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            flex: 1;
        }
        
        /* Custom Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 2px 15px rgba(67, 97, 238, 0.15);
            padding: 0.8rem 0;
        }
        
        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            padding: 0.5rem 0;
        }
        
        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .navbar-custom .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }
        
        .navbar-custom .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white !important;
        }
        
        .navbar-custom .dropdown-menu {
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 10px;
        }
        
        .navbar-custom .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: all 0.2s;
        }
        
        .navbar-custom .dropdown-item:hover {
            background-color: var(--primary);
            color: white;
            transform: translateX(5px);
        }
        
        .navbar-custom .btn-link.nav-link {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 0.5rem 1.5rem !important;
            margin-left: 0.5rem;
        }
        
        .navbar-custom .btn-link.nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        /* Main Content Styling */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 1.2rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-success-custom {
            background: linear-gradient(135deg, var(--success), #16a34a);
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }
        
        .table-custom thead th {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
        }
        
        .table-custom tbody tr {
            transition: background-color 0.2s;
        }
        
        .table-custom tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        /* Alert Styling */
        .alert-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
        }
        
        /* Form Styling */
        .form-control-custom {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            margin-top: 3rem;
            padding: 2rem 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-custom .nav-link {
                padding: 0.5rem !important;
                margin: 0.2rem 0;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        /* Utility Classes */
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }
        
        .text-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .shadow-soft {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @include('partials.nav')

    <main class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Toko Alzendi</h5>
                    <p class="text-white-50">Sistem manajemen toko retail modern untuk kebutuhan bisnis Anda.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-white-50 mb-0">© {{ date('Y') }} Toko Alzendi. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.navbar-custom .nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>