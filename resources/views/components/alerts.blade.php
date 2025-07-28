<!-- Success Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-3 fa-lg"></i>
            <div class="flex-grow-1">
                <strong>تم بنجاح!</strong>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Error Messages -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
            <div class="flex-grow-1">
                <strong>خطأ!</strong>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Warning Messages -->
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
            <div class="flex-grow-1">
                <strong>تحذير!</strong>
                <p class="mb-0">{{ session('warning') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Info Messages -->
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-3 fa-lg"></i>
            <div class="flex-grow-1">
                <strong>معلومات!</strong>
                <p class="mb-0">{{ session('info') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Validation Errors -->
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-start">
            <i class="fas fa-exclamation-triangle me-3 fa-lg mt-1"></i>
            <div class="flex-grow-1">
                <strong>يرجى إصلاح الأخطاء التالية:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<style>
.alert {
    border: none;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    font-weight: 500;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.alert::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 4px;
    height: 100%;
    background: currentColor;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    border-right: 4px solid var(--success-color);
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
    border-right: 4px solid var(--danger-color);
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
    border-right: 4px solid var(--warning-color);
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    color: #0c5460;
    border-right: 4px solid var(--info-color);
}

.alert-dismissible .btn-close {
    padding: 0.75rem;
    margin: -0.75rem -0.75rem -0.75rem auto;
    background: none;
    border: none;
    font-size: 1.25rem;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.alert-dismissible .btn-close:hover {
    opacity: 1;
}

.alert ul {
    list-style-type: none;
    padding-right: 0;
}

.alert ul li {
    position: relative;
    padding-right: 1.5rem;
    margin-bottom: 0.5rem;
}

.alert ul li:before {
    content: '•';
    position: absolute;
    right: 0;
    color: currentColor;
    font-weight: bold;
}

.alert ul li:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .alert .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .alert i {
        margin-bottom: 0.5rem;
        margin-right: 0 !important;
    }
}
</style> 