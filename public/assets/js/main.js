/**
 * Enhanced JavaScript for Gaza Coupon System
 * Modern interactions and functionality
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    
    // Hide loading overlay on page load
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
    
    // Initialize all components
    initNavbar();
    initSidebar();
    initAlerts();
    initForms();
    initTables();
    initAnimations();
    initBackToTop();
    initLoadingStates();
    
    // Hide loading overlay after page is fully loaded
    window.addEventListener('load', function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    });
    
});

/**
 * Initialize Navbar functionality
 */
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (!navbar) return;
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
    
    // Mobile menu improvements
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
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 992 && 
                !navbar.contains(event.target) && 
                navbarCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                bsCollapse.hide();
            }
        });
    }
    
    // Add active class to current nav item
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
}

/**
 * Initialize Sidebar functionality
 */
function initSidebar() {
    const sidebarContainer = document.getElementById('sidebar-container');
    
    if (!sidebarContainer) return;
    
    // Create toggle button for mobile
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'btn btn-primary sidebar-mobile-toggle d-lg-none position-fixed';
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    toggleBtn.style.cssText = 'top: 20px; right: 20px; z-index: 1001; border-radius: 50%; width: 50px; height: 50px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    
    // Add mobile toggle button
    document.body.appendChild(toggleBtn);
    
    // Get sidebar elements
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mainContent = document.querySelector('.main-content-with-sidebar');
    
    if (!sidebar || !sidebarToggle) return;
    
    // Load sidebar state from localStorage
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    // Apply initial state
    if (sidebarCollapsed) {
        sidebar.classList.add('collapsed');
        if (mainContent) {
            mainContent.classList.add('sidebar-collapsed');
        }
    }
    
    // Toggle sidebar function
    function toggleSidebar() {
        const isCollapsed = sidebar.classList.contains('collapsed');
        
        if (isCollapsed) {
            // Expand sidebar
            sidebar.classList.remove('collapsed');
            if (mainContent) {
                mainContent.classList.remove('sidebar-collapsed');
            }
            localStorage.setItem('sidebarCollapsed', 'false');
        } else {
            // Collapse sidebar
            sidebar.classList.add('collapsed');
            if (mainContent) {
                mainContent.classList.add('sidebar-collapsed');
            }
            localStorage.setItem('sidebarCollapsed', 'true');
        }
    }
    
    // Add click event to sidebar toggle button
    sidebarToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        toggleSidebar();
    });
    
    // Mobile sidebar functionality
    function toggleMobileSidebar() {
        if (window.innerWidth < 992) {
            sidebar.classList.toggle('show');
        }
    }
    
    // Add click event to mobile toggle button
    toggleBtn.addEventListener('click', toggleMobileSidebar);
    
    // Close mobile sidebar when clicking outside
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 992 && 
            !sidebar.contains(event.target) && 
            !toggleBtn.contains(event.target) &&
            sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('show');
            toggleBtn.style.display = 'none';
        } else {
            toggleBtn.style.display = 'block';
        }
    });
    
    // Initial mobile button visibility
    if (window.innerWidth < 992) {
        toggleBtn.style.display = 'block';
    } else {
        toggleBtn.style.display = 'none';
    }
    
    // Floating toggle button functionality
    const floatingToggle = document.getElementById('sidebar-toggle-expanded');
    if (floatingToggle) {
        // Show floating button when sidebar is collapsed
        function updateFloatingButton() {
            if (sidebar.classList.contains('collapsed') && window.innerWidth >= 992) {
                floatingToggle.classList.add('show');
            } else {
                floatingToggle.classList.remove('show');
            }
        }
        
        // Update floating button visibility
        updateFloatingButton();
        
        // Add click event to floating toggle
        floatingToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSidebar();
            updateFloatingButton();
        });
        
        // Update floating button on window resize
        window.addEventListener('resize', updateFloatingButton);
    }
}

/**
 * Initialize Alerts functionality
 */
function initAlerts() {
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
    
    // Add close button functionality
    const closeButtons = document.querySelectorAll('.btn-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const alert = this.closest('.alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        });
    });
}

/**
 * Initialize Forms functionality
 */
function initForms() {
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
        
        // Show loading overlay on form submission
        form.addEventListener('submit', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'flex';
            }
        });
    });
}

/**
 * Initialize Tables functionality
 */
function initTables() {
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
        
        // Add hover effects to table rows
        const tableRows = table.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    });
}

/**
 * Initialize Animations
 */
function initAnimations() {
    // Intersection Observer for scroll animations
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
    const animateElements = document.querySelectorAll('.card, .stats-card, .table, .btn');
    animateElements.forEach(el => {
        observer.observe(el);
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
}

/**
 * Initialize Back to Top button
 */
function initBackToTop() {
    const backToTopBtn = document.getElementById('back-to-top');
    
    if (!backToTopBtn) return;
    
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

/**
 * Initialize Loading States
 */
function initLoadingStates() {
    // Show loading overlay on form submissions (only for actual forms, not navigation links)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Only show loading for actual form submissions, not navigation
            if (form.action && form.action !== window.location.href) {
                const loadingOverlay = document.getElementById('loading-overlay');
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'flex';
                }
            }
        });
    });
    
    // Add loading states to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.classList.contains('btn-loading')) {
                const originalContent = this.innerHTML;
                this.innerHTML = '<span class="loading-spinner me-2"></span>جاري التحميل...';
                this.disabled = true;
                
                // Reset after 3 seconds (or replace with actual logic)
                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }, 3000);
            }
        });
    });
}

/**
 * Utility Functions
 */

// Refresh table function
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

// Export campaigns function
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
        showToast('تم تصدير جميع الحملات بنجاح!', 'success');
    }, 2000);
}

// Show toast notification
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        // Create toast container if it doesn't exist
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1060';
        document.body.appendChild(container);
    }
    
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">نظام قسائم غزة</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    document.getElementById('toast-container').innerHTML += toastHtml;
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

// Format currency
function formatCurrency(amount, currency = 'ILS') {
    return new Intl.NumberFormat('ar-IL', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

// Format date
function formatDate(date, locale = 'ar-IL') {
    return new Intl.DateTimeFormat(locale, {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Export functions to global scope
window.refreshTable = refreshTable;
window.exportAllCampaigns = exportAllCampaigns;
window.showToast = showToast;
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;
