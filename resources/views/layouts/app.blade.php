<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>نظام قسائم غزة - @yield('title', 'الرئيسية')</title>
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">
    <!-- Enhanced CSS -->
    <link href="{{ asset('assets/css/enhanced.css') }}" rel="stylesheet">
    <!-- Fixed CSS -->
    <link href="{{ asset('assets/css/fixed.css') }}" rel="stylesheet">
    
    <!-- Preload critical fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"></noscript>
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="نظام قسائم غزة - منصة رقمية متكاملة لإدارة القسائم والمساعدات الإنسانية">
    <meta name="keywords" content="قسائم غزة, مساعدات إنسانية, جمعيات خيرية, متاجر, مستفيدين">
    <meta name="author" content="نظام قسائم غزة">
    
    <!-- Open Graph tags -->
    <meta property="og:title" content="نظام قسائم غزة">
    <meta property="og:description" content="منصة رقمية متكاملة لإدارة القسائم والمساعدات الإنسانية">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Additional styles for specific pages -->
    @stack('styles')
</head>
<body>
    <!-- Loading overlay -->
    <div id="loading-overlay" class="position-fixed w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.9); z-index: 9999; display: none !important;">
        <div class="text-center">
            <div class="loading-spinner mb-3"></div>
            <p class="text-muted">جاري التحميل...</p>
        </div>
    </div>

    <!-- Navbar Component -->
    @include('components.navbar')

    <div class="d-flex flex-grow-1">
        @hasSection('sidebar')
            <!-- Sidebar Component -->
            <div id="sidebar-container">
                @component('components.sidebar')
                    @slot('title')
                        @yield('sidebar_title', 'لوحة التحكم')
                    @endslot
                    
                    @yield('sidebar')
                    
                    @slot('footer')
                        @yield('sidebar_footer')
                    @endslot
                @endcomponent
            </div>
            
            <div class="main-content main-content-with-sidebar flex-grow-1">
                <main class="py-4 px-3">
                    <!-- Breadcrumb -->
                    @hasSection('breadcrumb')
                        <nav aria-label="breadcrumb" class="mb-4">
                            <ol class="breadcrumb bg-transparent p-0">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif

                    <!-- Page Header -->
                    @hasSection('page_header')
                        <div class="page-header mb-4">
                            @yield('page_header')
                        </div>
                    @endif

                    <!-- Alerts Component -->
                    @include('components.alerts')

                    <!-- Main Content -->
                    @yield('content')
                </main>
            </div>
        @else
            <div class="main-content w-100">
                <main class="py-4 px-3">
                    <!-- Breadcrumb -->
                    @hasSection('breadcrumb')
                        <nav aria-label="breadcrumb" class="mb-4">
                            <ol class="breadcrumb bg-transparent p-0">
                                @yield('breadcrumb')
                            </ol>
                        </nav>
                    @endif

                    <!-- Page Header -->
                    @hasSection('page_header')
                        <div class="page-header mb-4">
                            @yield('page_header')
                        </div>
                    @endif

                    <!-- Alerts Component -->
                    @include('components.alerts')

                    <!-- Main Content -->
                    @yield('content')
                </main>
            </div>
        @endif
    </div>

    <!-- Footer Component -->
    @include('components.footer')

    <!-- Back to top button -->
    <button id="back-to-top" class="btn btn-primary position-fixed" style="bottom: 20px; left: 20px; z-index: 1000; border-radius: 50%; width: 50px; height: 50px; display: none; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    
    <script>
        // Enhanced JavaScript functionality
        
        // Show loading overlay on form submissions
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const loadingOverlay = document.getElementById('loading-overlay');
                    if (loadingOverlay) {
                        loadingOverlay.style.display = 'flex';
                    }
                });
            });
        });

        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link, .sidebar-fixed a');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active', 'active-link');
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
            const sidebarContainer = document.getElementById('sidebar-container');
            
            if (sidebarContainer) {
                // Create toggle button for mobile
                const toggleBtn = document.createElement('button');
                toggleBtn.className = 'btn btn-primary sidebar-mobile-toggle d-lg-none position-fixed';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.style.bottom = '20px';
                toggleBtn.style.right = '20px';
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
        
        // Back to top button
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopBtn = document.getElementById('back-to-top');
            
            if (backToTopBtn) {
                // Show/hide button based on scroll position
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTopBtn.style.display = 'block';
                    } else {
                        backToTopBtn.style.display = 'none';
                    }
                });
                
                // Smooth scroll to top when clicked
                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
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
        
        // Enhanced form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                const inputs = form.querySelectorAll('input, select, textarea');
                
                inputs.forEach(input => {
                    // Add visual feedback on focus
                    input.addEventListener('focus', function() {
                        this.parentElement.classList.add('focused');
                    });
                    
                    input.addEventListener('blur', function() {
                        this.parentElement.classList.remove('focused');
                    });
                    
                    // Real-time validation
                    input.addEventListener('input', function() {
                        if (this.checkValidity()) {
                            this.classList.remove('is-invalid');
                            this.classList.add('is-valid');
                        } else {
                            this.classList.remove('is-valid');
                            this.classList.add('is-invalid');
                        }
                    });
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
        
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, observerOptions);
            
            // Observe elements for animation
            const animateElements = document.querySelectorAll('.card, .stats-card, .table');
            animateElements.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
