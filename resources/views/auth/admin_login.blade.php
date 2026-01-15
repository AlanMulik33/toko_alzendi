<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Toko Alzendi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: linear-gradient(135deg, #f5f7fb 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .login-container {
            max-width: 420px;
            width: 100%;
        }
        
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(67, 97, 238, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(67, 97, 238, 0.2);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 2rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header-custom::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            opacity: 0.3;
        }
        
        .card-header-custom h3 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            position: relative;
        }
        
        .card-header-custom .bi-shield-lock {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .card-body {
            padding: 2.5rem;
            background-color: white;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control-custom {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-label {
            color: var(--gray);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(67, 97, 238, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .login-footer a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        .brand-logo {
            position: absolute;
            top: 20px;
            left: 20px;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
            text-decoration: none;
        }
        
        .brand-logo i {
            color: var(--secondary);
        }
        
        @media (max-width: 576px) {
            .card-body {
                padding: 2rem 1.5rem;
            }
            
            .card-header-custom {
                padding: 1.5rem;
            }
            
            body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <a href="/" class="brand-logo d-none d-md-block">
        <i class="bi bi-shop me-2"></i>Toko Alzendi
    </a>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="login-container mx-auto">
                    <div class="login-card">
                        <div class="card-header-custom">
                            <i class="bi bi-shield-lock"></i>
                            <h3>Admin Login</h3>
                            <p class="mb-0 opacity-75">Access the administration panel</p>
                        </div>
                        
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger alert-custom">
                                    <div class="d-flex">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            <strong class="d-block">Login Error</strong>
                                            <ul class="mb-0 mt-1 ps-3">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.login') }}">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="login" class="form-label">
                                        <i class="bi bi-person-circle me-1"></i>Username or Email
                                    </label>
                                    <input type="text" class="form-control form-control-custom" 
                                           id="login" name="login" required 
                                           placeholder="Enter your username or email">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-key me-1"></i>Password
                                    </label>
                                    <input type="password" class="form-control form-control-custom" 
                                           id="password" name="password" required 
                                           placeholder="Enter your password">
                                </div>
                                
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me on this device
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-login">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to Dashboard
                                </button>
                                
                                <div class="login-footer">
                                    <p class="mb-0">
                                        Return to 
                                        <a href="{{ route('customer.login') }}">Customer Login</a>
                                        or 
                                        <a href="/">Homepage</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add focus effect to form inputs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control-custom');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
            
            // Auto focus on login input
            document.getElementById('login')?.focus();
        });
    </script>
</body>
</html>