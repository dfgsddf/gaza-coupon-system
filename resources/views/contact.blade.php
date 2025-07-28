@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fa-solid fa-envelope-open-text me-3"></i>
                    تواصل معنا
                </h1>
                <p class="lead mb-0">نحن هنا لمساعدتك! لا تتردد في التواصل معنا لأي استفسار أو مساعدة</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center gy-5">
        <!-- Contact Form -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; backdrop-filter: blur(10px);">
                <div class="card-body p-5">
                    <!-- Form Header -->
                    <div class="text-center mb-5">
                        <div class="bg-gradient-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 90px; height: 90px; box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);">
                            <i class="fa-solid fa-paper-plane fa-2x"></i>
                        </div>
                        <h2 class="fw-bold text-primary mb-3">أرسل لنا رسالة</h2>
                        <p class="text-muted fs-5">سنكون سعداء بالرد عليك في أقرب وقت ممكن</p>
                    </div>

                    <!-- Contact Form -->
                    <form method="POST" action="{{ route('contact.submit') }}" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label fw-semibold text-primary">
                                    <i class="fa-solid fa-user me-2"></i>الاسم الكامل
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="أدخل اسمك الكامل"
                                       style="border-radius: 15px; border: 2px solid #e9ecef; transition: all 0.3s ease;"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-semibold text-primary">
                                    <i class="fa-solid fa-envelope me-2"></i>البريد الإلكتروني
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="أدخل بريدك الإلكتروني"
                                       style="border-radius: 15px; border: 2px solid #e9ecef; transition: all 0.3s ease;"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Subject Field -->
                        <div class="mb-4">
                            <label for="subject" class="form-label fw-semibold text-primary">
                                <i class="fa-solid fa-tag me-2"></i>الموضوع
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   placeholder="أدخل موضوع الرسالة"
                                   style="border-radius: 15px; border: 2px solid #e9ecef; transition: all 0.3s ease;"
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message Field -->
                        <div class="mb-5">
                            <label for="message" class="form-label fw-semibold text-primary">
                                <i class="fa-solid fa-comment me-2"></i>الرسالة
                            </label>
                            <textarea class="form-control form-control-lg @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      placeholder="أخبرنا كيف يمكننا مساعدتك..."
                                      style="border-radius: 15px; border: 2px solid #e9ecef; transition: all 0.3s ease; resize: none;"
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" 
                                    style="border-radius: 15px; padding: 15px; font-size: 1.1rem; font-weight: 600; box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3); transition: all 0.3s ease;">
                                <i class="fa-solid fa-paper-plane me-2"></i>إرسال الرسالة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg h-100" style="border-radius: 20px; backdrop-filter: blur(10px);">
                <div class="card-body p-5">
                    <!-- Info Header -->
                    <div class="text-center mb-5">
                        <div class="bg-gradient-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 90px; height: 90px; box-shadow: 0 10px 30px rgba(13, 202, 240, 0.3);">
                            <i class="fa-solid fa-info fa-2x"></i>
                        </div>
                        <h2 class="fw-bold text-info mb-3">معلومات الاتصال</h2>
                        <p class="text-muted fs-5">تواصل معنا من خلال أي من هذه القنوات</p>
                    </div>

                    <!-- Contact Details -->
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 rounded-3" 
                                 style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6; transition: all 0.3s ease;">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-4" 
                                     style="width: 60px; height: 60px; box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);">
                                    <i class="fa-solid fa-map-marker-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-primary">العنوان</h6>
                                    <p class="text-muted mb-0 fs-6">قطاع غزة، فلسطين</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 rounded-3" 
                                 style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6; transition: all 0.3s ease;">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-4" 
                                     style="width: 60px; height: 60px; box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);">
                                    <i class="fa-solid fa-phone fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-success">الهاتف</h6>
                                    <p class="text-muted mb-0 fs-6">+970 59 123 4567</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 rounded-3" 
                                 style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6; transition: all 0.3s ease;">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-4" 
                                     style="width: 60px; height: 60px; box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);">
                                    <i class="fa-solid fa-envelope fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-warning">البريد الإلكتروني</h6>
                                    <p class="text-muted mb-0 fs-6">info@gazacoupon.com</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 rounded-3" 
                                 style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6; transition: all 0.3s ease;">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-4" 
                                     style="width: 60px; height: 60px; box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);">
                                    <i class="fa-solid fa-clock fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-danger">ساعات العمل</h6>
                                    <p class="text-muted mb-0 fs-6">الأحد - الخميس: 8 صباحاً - 6 مساءً</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="text-center mt-5 pt-4" style="border-top: 2px solid #e9ecef;">
                        <h6 class="fw-bold mb-4 text-primary">تابعنا على وسائل التواصل الاجتماعي</h6>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="btn btn-outline-primary rounded-circle" 
                               style="width: 50px; height: 50px; transition: all 0.3s ease; box-shadow: 0 3px 10px rgba(0, 123, 255, 0.2);">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info rounded-circle" 
                               style="width: 50px; height: 50px; transition: all 0.3s ease; box-shadow: 0 3px 10px rgba(13, 202, 240, 0.2);">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger rounded-circle" 
                               style="width: 50px; height: 50px; transition: all 0.3s ease; box-shadow: 0 3px 10px rgba(220, 53, 69, 0.2);">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary rounded-circle" 
                               style="width: 50px; height: 50px; transition: all 0.3s ease; box-shadow: 0 3px 10px rgba(0, 123, 255, 0.2);">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div class="alert alert-success alert-dismissible fade show" role="alert" 
         style="border-radius: 15px; box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);">
        <i class="fa-solid fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div class="alert alert-danger alert-dismissible fade show" role="alert" 
         style="border-radius: 15px; box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);">
        <i class="fa-solid fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<style>
/* Custom Styles for Contact Page */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    transform: translateY(-2px);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4) !important;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
}

.contact-item:hover {
    transform: translateX(5px);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
}

.social-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .lead {
        font-size: 1rem;
    }
    
    .card-body {
        padding: 2rem !important;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1.5rem !important;
    }
    
    .bg-gradient-primary, .bg-gradient-info {
        width: 70px !important;
        height: 70px !important;
    }
}
</style>

<script>
// Add hover effects and animations
document.addEventListener('DOMContentLoaded', function() {
    // Form field animations
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        control.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Contact items hover effects
    const contactItems = document.querySelectorAll('.d-flex.align-items-center.p-3');
    contactItems.forEach(item => {
        item.classList.add('contact-item');
    });
    
    // Social buttons hover effects
    const socialBtns = document.querySelectorAll('.btn-outline-primary, .btn-outline-info, .btn-outline-danger');
    socialBtns.forEach(btn => {
        btn.classList.add('social-btn');
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentElement) {
                alert.parentElement.remove();
            }
        }, 5000);
    });
});
</script>
@endsection
