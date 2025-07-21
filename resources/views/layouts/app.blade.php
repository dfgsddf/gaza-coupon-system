<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gaza Coupon</title>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- FontAwesome (اختياري للرموز) -->
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <style>
        .sidebar-fixed {
            width: 220px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            background: #0d6efd;
            color: #fff;
        }
        .main-content {
            margin-left: 220px;
            min-height: 100vh;
        }
        @media (max-width: 991.98px) {
            .sidebar-fixed {
                position: static;
                width: 100%;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">GAZA COUPON</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link">Contact Us</a></li>
                <li class="nav-item"><a href="{{ url('/help') }}" class="nav-link">Help & Support</a></li>
                <li class="nav-item"><a href="{{ route('store.dashboard') }}" class="nav-link">Store</a></li>
                <li class="nav-item"><a href="{{ route('beneficiary.dashboard') }}" class="nav-link">Beneficiary</a></li>
                <li class="nav-item"><a href="{{ route('charity.dashboard') }}" class="nav-link">Charity</a></li>
                <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link">Admin</a></li>
                @guest
                    <li class="nav-item"><a href="{{ route('login.form') }}" class="nav-link btn btn-primary ms-2">Login</a></li>
                @endguest
                @if(app()->environment('local', 'development'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="forceLoginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Force Login
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="forceLoginDropdown">
                            <li><a class="dropdown-item" href="/force-login">Admin</a></li>
                            <li><a class="dropdown-item" href="/force-login-store">Store</a></li>
                            <li><a class="dropdown-item" href="/force-login-beneficiary">Beneficiary</a></li>
                            <li><a class="dropdown-item" href="/force-login-charity">Charity</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<div class="d-flex">
    @hasSection('sidebar')
        <div class="sidebar-fixed">
            @yield('sidebar')
        </div>
    @endif
    <div class="main-content flex-grow-1">
        <main class="py-4">
            <!-- Success Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Error Messages -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<!-- Scripts -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
