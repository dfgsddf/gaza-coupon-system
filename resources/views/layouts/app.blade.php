<!DOCTYPE html>
<html lang="en" dir="rtl">
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
    
    <style>
        /* RTL Improvements */
        .rtl-text {
            direction: rtl;
            text-align: right;
        }
        
        .ltr-text {
            direction: ltr;
            text-align: left;
        }
        
        /* Mobile-first improvements */
        @media (max-width: 768px) {
            .mobile-stack {
                flex-direction: column !important;
            }
            
            .mobile-center {
                text-align: center !important;
            }
            
            .mobile-full-width {
                width: 100% !important;
            }
            
            .mobile-margin {
                margin: 0.5rem 0 !important;
            }
            
            .mobile-padding {
                padding: 0.5rem !important;
            }
        }
        
        /* Loading animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
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
    <!-- jQuery (required for charity campaigns page) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                if (bsAlert) {
                    bsAlert.close();
                }
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
                toggleBtn.className = 'btn btn-primary sidebar-mobile-toggle d-lg-none position-fixed';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.style.bottom = '20px';
                toggleBtn.style.left = '20px';
                toggleBtn.style.zIndex = '1050';
                toggleBtn.style.borderRadius = '50%';
                toggleBtn.style.width = '50px';
                toggleBtn.style.height = '50px';
                toggleBtn.style.padding = '0';
                toggleBtn.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.3)';
                toggleBtn.setAttribute('aria-label', 'Toggle Sidebar');
                
                document.body.appendChild(toggleBtn);
                
                // Toggle sidebar on button click
                toggleBtn.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar-fixed');
                    if (sidebar) {
                        sidebar.classList.toggle('show');
                        
                        // Update button icon
                        const icon = toggleBtn.querySelector('i');
                        if (sidebar.classList.contains('show')) {
                            icon.className = 'fas fa-times';
                        } else {
                            icon.className = 'fas fa-bars';
                        }
                    }
                });
                
                // Close sidebar when clicking outside
                document.addEventListener('click', function(event) {
                    const sidebar = document.querySelector('.sidebar-fixed');
                    if (sidebar && sidebar.classList.contains('show') && 
                        !sidebar.contains(event.target) && 
                        event.target !== toggleBtn && 
                        !toggleBtn.contains(event.target)) {
                        sidebar.classList.remove('show');
                        const icon = toggleBtn.querySelector('i');
                        icon.className = 'fas fa-bars';
                    }
                });
                
                // Close sidebar on escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        const sidebar = document.querySelector('.sidebar-fixed');
                        if (sidebar && sidebar.classList.contains('show')) {
                            sidebar.classList.remove('show');
                            const icon = toggleBtn.querySelector('i');
                            icon.className = 'fas fa-bars';
                        }
                    }
                });
            }
        });
        
        // Table responsive improvements
        document.addEventListener('DOMContentLoaded', function() {
            const tables = document.querySelectorAll('.table-responsive');
            tables.forEach(table => {
                // Add horizontal scroll indicator
                const scrollIndicator = document.createElement('div');
                scrollIndicator.className = 'table-scroll-indicator d-lg-none text-center text-muted small py-2';
                scrollIndicator.innerHTML = '<i class="fas fa-arrow-left me-1"></i>اسحب يميناً ويساراً لعرض المزيد<i class="fas fa-arrow-right ms-1"></i>';
                table.parentNode.insertBefore(scrollIndicator, table.nextSibling);
                
                // Hide indicator when table is fully scrolled
                table.addEventListener('scroll', function() {
                    if (table.scrollWidth <= table.clientWidth) {
                        scrollIndicator.style.display = 'none';
                    } else {
                        scrollIndicator.style.display = 'block';
                    }
                });
            });
        });
        
        // Performance improvements for tables
        function refreshTable() {
            const loadingBtn = event.target;
            const originalContent = loadingBtn.innerHTML;
            
            loadingBtn.innerHTML = '<span class="loading-spinner me-2"></span>جاري التحديث...';
            loadingBtn.disabled = true;
            
            // Simulate refresh (replace with actual refresh logic)
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
        
        function exportAllCampaigns() {
            const exportBtn = event.target;
            const originalContent = exportBtn.innerHTML;
            
            exportBtn.innerHTML = '<span class="loading-spinner me-2"></span>جاري التصدير...';
            exportBtn.disabled = true;
            
            // Add your export logic here
            setTimeout(() => {
                exportBtn.innerHTML = originalContent;
                exportBtn.disabled = false;
                
                // Show success message
                const toast = document.querySelector('#campaigns-toast');
                if (toast) {
                    toast.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-info');
                    toast.classList.add('text-bg-success');
                    document.querySelector('#campaigns-toast-body').textContent = 'تم تصدير جميع الحملات بنجاح!';
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                }
            }, 2000);
        }
    </script>
    
    @stack('scripts')
</body>
</html>
