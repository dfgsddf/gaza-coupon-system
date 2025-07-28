<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gaza Coupon System</title>
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">
    <!-- Unified CSS (combines app.css, enhanced.css, and centering.css) -->
    <link href="{{ asset('assets/css/unified.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar Component -->
    @include('components.navbar')

    <div class="d-flex flex-grow-1">
        @hasSection('sidebar')
            <!-- Sidebar Component -->
            <div id="sidebar-container">
                @component('components.sidebar')
                    @slot('title')
                        @yield('sidebar_title', 'Dashboard')
                    @endslot
                    
                    @yield('sidebar')
                    
                    @slot('footer')
                        @yield('sidebar_footer')
                    @endslot
                @endcomponent
            </div>
            
            <div class="main-content main-content-with-sidebar flex-grow-1">
                <main class="py-4 px-3">
                    <!-- Alerts Component -->
                    @include('components.alerts')

                    @yield('content')
                </main>
            </div>
        @else
            <div class="main-content w-100">
                <main class="py-4 px-3">
                    <!-- Alerts Component -->
                    @include('components.alerts')

                    @yield('content')
                </main>
            </div>
        @endif
    </div>

    <!-- Footer Component -->
    @include('components.footer')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    
    <script>
        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Mobile menu improvements
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                // Close mobile menu when clicking on a link
                const navLinks = navbarCollapse.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 992) {
                            const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                            bsCollapse.hide();
                        }
                    });
                });
            }
        });

        // Sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Add sidebar toggle button for mobile
            const sidebarContainer = document.getElementById('sidebar-container');
            
            if (sidebarContainer) {
                // Create toggle button for mobile
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'btn btn-primary sidebar-mobile-toggle d-lg-none';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.style.position = 'fixed';
                toggleBtn.style.bottom = '20px';
                toggleBtn.style.right = '20px';
                toggleBtn.style.zIndex = '1000';
                toggleBtn.style.borderRadius = '50%';
                toggleBtn.style.width = '50px';
                toggleBtn.style.height = '50px';
                toggleBtn.style.padding = '0';
                toggleBtn.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
                
                document.body.appendChild(toggleBtn);
                
                // Toggle sidebar on button click
                toggleBtn.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar-fixed');
                    if (sidebar) {
                        sidebar.classList.toggle('show');
                    }
                });
                
                // Close sidebar when clicking outside
                document.addEventListener('click', function(event) {
                    const sidebar = document.querySelector('.sidebar-fixed');
                    if (sidebar && sidebar.classList.contains('show') && 
                        !sidebar.contains(event.target) && 
                        event.target !== toggleBtn) {
                        sidebar.classList.remove('show');
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
