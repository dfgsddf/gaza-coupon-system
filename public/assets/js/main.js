/**
 * Main JavaScript file for Gaza Coupon System
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tooltips
    initTooltips();
    
    // Initialize navbar scroll effect
    initNavbarScroll();
    
    // Initialize sidebar functionality
    initSidebar();
    
    // Initialize form validations
    initFormValidation();
    
    // Add animation classes to elements
    initAnimations();
});

/**
 * Initialize Bootstrap tooltips
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    if (tooltipTriggerList.length > 0) {
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

/**
 * Add scroll effect to navbar
 */
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }
}

/**
 * Initialize sidebar functionality
 */
function initSidebar() {
    const sidebarContainer = document.getElementById('sidebar-container');
    
    if (sidebarContainer) {
        // Handle sidebar links active state
        const sidebarLinks = sidebarContainer.querySelectorAll('a');
        const currentPath = window.location.pathname;
        
        sidebarLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPath || currentPath.startsWith(href) && href !== '/') {
                link.classList.add('active-link');
            }
        });
    }
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    if (forms.length > 0) {
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    }
}

/**
 * Add animation classes to elements
 */
function initAnimations() {
    // Add animation to elements with data-animate attribute
    const animatedElements = document.querySelectorAll('[data-animate]');
    
    if (animatedElements.length > 0) {
        animatedElements.forEach((element, index) => {
            const animation = element.getAttribute('data-animate');
            const delay = element.getAttribute('data-delay') || index * 0.1;
            
            element.style.animationDelay = `${delay}s`;
            element.classList.add(animation);
        });
    }
    
    // Create intersection observer for scroll animations
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                const animation = element.getAttribute('data-scroll-animate');
                
                if (animation) {
                    element.classList.add(animation);
                    observer.unobserve(element);
                }
            }
        });
    }, observerOptions);
    
    // Observe elements with data-scroll-animate attribute
    const scrollAnimatedElements = document.querySelectorAll('[data-scroll-animate]');
    
    if (scrollAnimatedElements.length > 0) {
        scrollAnimatedElements.forEach(element => {
            observer.observe(element);
        });
    }
}

/**
 * Show loading spinner on button
 * @param {HTMLElement} button - Button element
 * @param {boolean} isLoading - Loading state
 */
function buttonLoading(button, isLoading) {
    if (isLoading) {
        const loadingSpinner = document.createElement('span');
        loadingSpinner.className = 'loading-spinner me-2';
        button.prepend(loadingSpinner);
        button.disabled = true;
    } else {
        const spinner = button.querySelector('.loading-spinner');
        if (spinner) {
            spinner.remove();
        }
        button.disabled = false;
    }
}

/**
 * Format currency value
 * @param {number} value - Value to format
 * @param {string} currency - Currency code (default: USD)
 * @returns {string} Formatted currency string
 */
function formatCurrency(value, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency
    }).format(value);
}

/**
 * Format date value
 * @param {string|Date} date - Date to format
 * @param {object} options - Intl.DateTimeFormat options
 * @returns {string} Formatted date string
 */
function formatDate(date, options = {}) {
    const defaultOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    };
    
    const mergedOptions = { ...defaultOptions, ...options };
    
    return new Intl.DateTimeFormat('en-US', mergedOptions).format(new Date(date));
}

/**
 * Handle form submission with AJAX
 * @param {HTMLFormElement} form - Form element
 * @param {Function} successCallback - Success callback function
 * @param {Function} errorCallback - Error callback function
 */
function handleFormSubmit(form, successCallback, errorCallback) {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            buttonLoading(submitButton, true);
        }
        
        const formData = new FormData(form);
        const url = form.getAttribute('action');
        const method = form.getAttribute('method') || 'POST';
        
        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (submitButton) {
                buttonLoading(submitButton, false);
            }
            
            if (successCallback && typeof successCallback === 'function') {
                successCallback(data);
            }
        })
        .catch(error => {
            if (submitButton) {
                buttonLoading(submitButton, false);
            }
            
            if (errorCallback && typeof errorCallback === 'function') {
                errorCallback(error);
            }
        });
    });
}
