<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}" />
    <style>
        .sidebar {
            height: 100vh;
            background-color: #0d47a1;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        .topbar {
            background-color: #0d47a1;
            color: white;
            padding: 10px 20px;
        }
        .stats-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            border-radius: 10px;
            padding: 5px 10px;
            font-size: 0.9rem;
        }
        .status-proccessing {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<!-- Navbar -->
@include('partials.navbar')

<!-- Content -->
@yield('content')

<!-- Footer -->
<footer class="text-center text-muted py-3">
    Gaza Coupon Management System - 2025 &copy;
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
