@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card shadow-lg border-0" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-sign-in-alt fa-2x"></i>
                        </div>
                        <h2 class="fw-bold text-primary mb-2">Welcome Back</h2>
                        <p class="text-muted">Sign in to your account</p>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Email Field -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fa-solid fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fa-solid fa-lock me-2"></i>Password
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            {{-- <a href="{{ route('password.request') }}" class="text-decoration-none text-primary fw-semibold d-block mb-2">
                                <i class="fa-solid fa-key me-1"></i>Forgot Password?
                            </a> --}}
                            <a href="{{ route('register') }}" class="text-decoration-none text-primary fw-semibold">
                                <i class="fa-solid fa-user-plus me-1"></i>Register as new user
                            </a>
                        </div>
                    </form>
                    
                    <!-- Test Accounts Section -->
                    <div class="mt-4">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white text-center">
                                <h6 class="mb-0">
                                    <i class="fa-solid fa-flask me-2"></i>
                                    حسابات تجريبية للاختبار
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="fillForm('admin@example.com', 'password')">
                                            <i class="fa-solid fa-user-shield me-1"></i>مشرف
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="fillForm('beneficiary@example.com', 'password')">
                                            <i class="fa-solid fa-users me-1"></i>مستفيد
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-info btn-sm w-100" onclick="fillForm('store@example.com', 'password')">
                                            <i class="fa-solid fa-store me-1"></i>متجر
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-outline-warning btn-sm w-100" onclick="fillForm('charity@example.com', 'password')">
                                            <i class="fa-solid fa-building me-1"></i>جمعية
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card styling */
    .card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Form control styling */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus {
        border-color: #2A61CC;
        box-shadow: 0 0 0 0.2rem rgba(42, 97, 204, 0.25);
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-2px);
    }
    
    /* Button styling */
    .btn {
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2A61CC 0%, #1e4ba3 100%);
        border: none;
        box-shadow: 0 4px 15px rgba(42, 97, 204, 0.3);
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(42, 97, 204, 0.4);
        background: linear-gradient(135deg, #1e4ba3 0%, #2A61CC 100%);
    }
    
    .btn-outline-secondary {
        border: 2px solid #e9ecef;
        border-left: none;
        border-radius: 0 12px 12px 0;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #2A61CC;
    }
    
    /* Input group styling */
    .input-group .form-control {
        border-right: none;
        border-radius: 12px 0 0 12px;
    }
    
    .input-group .btn {
        border-radius: 0 12px 12px 0;
    }
    
    /* Form check styling */
    .form-check-input:checked {
        background-color: #2A61CC;
        border-color: #2A61CC;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 2rem !important;
        }
        
        .form-control {
            font-size: 1rem;
        }
        
        .btn {
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .form-control {
            font-size: 0.9rem;
        }
        
        .btn {
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
        }
    }
</style>

<script>
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Bootstrap form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    
    // Auto focus on email field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('email').focus();
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill form if email and password are provided in URL
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email');
    const password = urlParams.get('password');
    
    if (email && password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
        
        // Show a notification
        const notification = document.createElement('div');
        notification.className = 'alert alert-info alert-dismissible fade show';
        notification.innerHTML = `
            <i class="fa-solid fa-info-circle me-2"></i>
            تم ملء النموذج تلقائياً بالحساب التجريبي
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.card-body').insertBefore(notification, document.querySelector('.card-body').firstChild);
    }
});

// Function to fill form with test account
function fillForm(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
    
    // Show a notification
    const notification = document.createElement('div');
    notification.className = 'alert alert-success alert-dismissible fade show';
    notification.innerHTML = `
        <i class="fa-solid fa-check-circle me-2"></i>
        تم ملء النموذج بالحساب التجريبي: ${email}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.card-body').insertBefore(notification, document.querySelector('.card-body').firstChild);
}
</script>

@endsection
