<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register - Toko Alzendi</title>
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
            padding: 1rem;
        }
        
        .register-container {
            max-width: 520px;
            width: 100%;
            margin: 0 auto;
        }
        
        .register-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(67, 97, 238, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .register-card:hover {
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
        
        .card-header-custom .bi-person-plus {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .card-header-custom p {
            opacity: 0.85;
            font-size: 0.95rem;
            margin-bottom: 0;
            position: relative;
        }
        
        .card-body {
            padding: 2.5rem;
            background-color: white;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-label .required {
            color: var(--danger);
            margin-left: 4px;
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
        
        textarea.form-control-custom {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-text-custom {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(67, 97, 238, 0.3);
        }
        
        .btn-register:active {
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
        
        .register-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-light);
            color: var(--gray);
        }
        
        .register-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .register-footer a:hover {
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
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 576px) {
            .card-body {
                padding: 2rem 1.5rem;
            }
            
            .card-header-custom {
                padding: 1.5rem;
            }
            
            .brand-logo {
                position: relative;
                top: 0;
                left: 0;
                text-align: center;
                margin-bottom: 1.5rem;
                display: block;
            }
            
            body {
                padding: 0.5rem;
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
                <div class="register-container">
                    <div class="register-card">
                        <div class="card-header-custom">
                            <i class="bi bi-person-plus"></i>
                            <h3>Create Account</h3>
                            <p>Join Toko Alzendi and start shopping</p>
                        </div>
                        
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger alert-custom">
                                    <div class="d-flex">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            <strong class="d-block">Registration Error</strong>
                                            <ul class="mb-0 mt-1 ps-3">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('customer.register') }}">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">
                                                <i class="bi bi-person me-1"></i>Full Name
                                            </label>
                                            <input type="text" class="form-control form-control-custom" 
                                                   id="name" name="name" required 
                                                   placeholder="Enter your full name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username" class="form-label">
                                                <i class="bi bi-person-badge me-1"></i>Username
                                            </label>
                                            <input type="text" class="form-control form-control-custom" 
                                                   id="username" name="username" required 
                                                   placeholder="Choose a username">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>Email Address
                                    </label>
                                    <input type="email" class="form-control form-control-custom" 
                                           id="email" name="email" required 
                                           placeholder="Enter your email">
                                </div>
                                
                                <div class="form-group">
                                    <label for="address" class="form-label">
                                        <i class="bi bi-geo-alt me-1"></i>Address <span class="required">*</span>
                                    </label>
                                    <textarea class="form-control form-control-custom" 
                                              id="address" name="address" rows="3" required 
                                              placeholder="Enter your complete address"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone me-1"></i>Phone Number
                                    </label>
                                    <input type="text" class="form-control form-control-custom" 
                                           id="phone" name="phone" 
                                           placeholder="Optional phone number">
                                        </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            <i class="bi bi-key me-1"></i>Password
                                        </label>
                                            <input type="password" class="form-control form-control-custom" 
                                                id="password" name="password" required 
                                                placeholder="Create a password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="bi bi-key-fill me-1"></i>Confirm Password
                                        </label>
                                            <input type="password" class="form-control form-control-custom" 
                                                id="password_confirmation" name="password_confirmation" required 
                                                placeholder="Re-enter your password">
                                    </div>
                                </div>
                            </div>
                                
                                
                                <button type="submit" class="btn btn-register">
                                    <i class="bi bi-person-plus-fill me-2"></i>Create Account
                                </button>
                                
                                <div class="register-footer">
                                    <p class="mb-0">
                                        Already have an account? 
                                        <a href="{{ route('customer.login') }}">Login here</a>
                                    </p>
                                    <p class="mt-2 mb-0">
                                        <a href="/">
                                            <i class="bi bi-arrow-left me-1"></i>Back to Homepage
                                        </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus on first input
            document.getElementById('name')?.focus();
            
            // Password confirmation check
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            
            function checkPasswordMatch() {
                if (password.value && confirmPassword.value) {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.style.borderColor = 'var(--danger)';
                        confirmPassword.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.15)';
                    } else {
                        confirmPassword.style.borderColor = 'var(--success)';
                        confirmPassword.style.boxShadow = '0 0 0 3px rgba(74, 222, 128, 0.15)';
                    }
                }
            }
            
            password.addEventListener('input', checkPasswordMatch);
            confirmPassword.addEventListener('input', checkPasswordMatch);
        });
    </script>
</body>
</html>