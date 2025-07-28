@extends('layouts.app')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5">
        <div class="container d-flex flex-column align-items-center justify-content-center" style="max-width: 1200px;">
            <div class="row justify-content-center align-items-center w-100">
                <div class="col-lg-8 text-center mx-auto">
                    <div class="fade-in-up">
                        <h1 class="display-4 fw-bold mb-4">نظام قسائم غزة</h1>
                        <p class="lead mb-4">
                            ربط الجمعيات الخيرية والمتاجر والمستفيدين من خلال منصة آمنة وفعالة لإدارة القسائم.
                            دعم المجتمع من خلال برامج المساعدة الرقمية.
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="{{ route('beneficiary.register.form') }}" class="btn btn-light btn-lg">
                                <i class="fa-solid fa-user-plus me-2"></i>سجل كمستفيد
                            </a>
                           
                            <a href="{{ route('login.form') }}" class="btn btn-outline-light btn-lg">
                                <i class="fa-solid fa-sign-in-alt me-2"></i>تسجيل الدخول
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center mt-4 mt-lg-0 mx-auto">
                    <div class="fade-in-up">
                        <i class="fa-solid fa-hand-holding-heart fa-8x text-light opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container" style="max-width: 1200px;">
            <div class="text-center mb-5 fade-in-up">
                <h2 class="fw-bold">كيف يعمل النظام</h2>
                <p class="lead text-muted">منصتنا تربط جميع الأطراف المعنية في نظام المساعدة</p>
            </div>
            
            <div class="row justify-content-center g-4">
                <div class="col-lg-4 col-md-6 fade-in-up">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-building fa-2x"></i>
                            </div>
                            <h5 class="card-title fw-bold">الجمعيات الخيرية</h5>
                            <p class="card-text text-muted">
                                إنشاء وإدارة حملات المساعدة، تتبع التبرعات، وإنشاء التقارير لمساعدة المحتاجين.
                            </p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                <i class="fa-solid fa-arrow-right me-2"></i>انضم كجمعية خيرية
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 fade-in-up">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-store fa-2x"></i>
                            </div>
                            <h5 class="card-title fw-bold">المتاجر</h5>
                            <p class="card-text text-muted">
                                قبول القسائم الرقمية، التحقق من المعاملات، وإدارة مشاركتك في برامج المساعدة.
                            </p>
                            <a href="{{ route('register') }}" class="btn btn-outline-success">
                                <i class="fa-solid fa-arrow-right me-2"></i>انضم كمتجر
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 fade-in-up">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-users fa-2x"></i>
                            </div>
                            <h5 class="card-title fw-bold">المستفيدون</h5>
                            <p class="card-text text-muted">
                                طلب المساعدة، استلام القسائم الرقمية، واستخدامها في المتاجر المشاركة للمواد الأساسية.
                            </p>
                            <a href="{{ route('beneficiary.register.form') }}" class="btn btn-outline-info">
                                <i class="fa-solid fa-arrow-right me-2"></i>سجل كمستفيد
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="bg-light py-5">
        <div class="container" style="max-width: 1200px;">
            <div class="row justify-content-center text-center w-100 mx-auto">
                <div class="col-lg-3 col-md-6 mb-4 fade-in-up">
                    <div class="stats-card">
                        <i class="fa-solid fa-building text-primary"></i>
                        <h3 class="fw-bold text-primary">50+</h3>
                        <p class="text-muted mb-0 fw-semibold">جمعية خيرية</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 fade-in-up">
                    <div class="stats-card">
                        <i class="fa-solid fa-store text-success"></i>
                        <h3 class="fw-bold text-success">200+</h3>
                        <p class="text-muted mb-0 fw-semibold">متجر</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 fade-in-up">
                    <div class="stats-card">
                        <i class="fa-solid fa-users text-info"></i>
                        <h3 class="fw-bold text-info">10,000+</h3>
                        <p class="text-muted mb-0 fw-semibold">مستفيد</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 fade-in-up">
                    <div class="stats-card">
                        <i class="fa-solid fa-ticket text-warning"></i>
                        <h3 class="fw-bold text-warning">50,000+</h3>
                        <p class="text-muted mb-0 fw-semibold">قسيمة صادرة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container" style="max-width: 1200px;">
            <div class="text-center mb-5 fade-in-up">
                <h2 class="fw-bold">عملية بسيطة</h2>
                <p class="lead text-muted">ثلاث خطوات سهلة للحصول على المساعدة</p>
            </div>
            
            <div class="row justify-content-center g-4 w-100 mx-auto">
                <div class="col-lg-4 col-md-6 text-center fade-in-up">
                    <div class="position-relative">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="fw-bold fs-4">1</span>
                        </div>
                        <h5 class="fw-bold">التسجيل</h5>
                        <p class="text-muted">إنشاء حسابك كمستفيد وتوفير المعلومات اللازمة.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 text-center fade-in-up">
                    <div class="position-relative">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="fw-bold fs-4">2</span>
                        </div>
                        <h5 class="fw-bold">طلب المساعدة</h5>
                        <p class="text-muted">تقديم طلب المساعدة وانتظار الموافقة من الجمعيات الخيرية.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 text-center fade-in-up">
                    <div class="position-relative">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="fw-bold fs-4">3</span>
                        </div>
                        <h5 class="fw-bold">استخدام القسائم</h5>
                        <p class="text-muted">استلام القسائم الرقمية واستخدامها في المتاجر المشاركة.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-primary text-white py-5">
        <div class="container text-center" style="max-width: 900px;">
            <div class="fade-in-up">
                <h2 class="fw-bold mb-4">مستعد للبدء؟</h2>
                <p class="lead mb-4">انضم إلى الآلاف من الأشخاص الذين يستفيدون بالفعل من منصتنا</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('beneficiary.register.form') }}" class="btn btn-light btn-lg">
                        <i class="fa-solid fa-user-plus me-2"></i>سجل الآن كمستفيد
                    </a>
                    <a href="{{ route('contact.show') }}" class="btn btn-outline-light btn-lg">
                        <i class="fa-solid fa-envelope me-2"></i>اتصل بنا
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Info -->
    <section class="bg-dark text-white py-5">
        <div class="container" style="max-width: 1000px;">
            <div class="row justify-content-center text-center">
                <div class="col-12 mb-4">
                    <h4 class="fw-bold mb-3">
                        <i class="fa-solid fa-ticket-alt me-3"></i>نظام قسائم غزة
                    </h4>
                    <p class="lead text-muted mb-4" style="font-size: 1.2rem;">
                        ربط المجتمعات من خلال المساعدة الرقمية
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-4 mb-4">
                        <a href="{{ route('contact.show') }}" class="text-light text-decoration-none btn btn-outline-light">
                            <i class="fa-solid fa-envelope me-2"></i>اتصل بنا
                        </a>
                        <a href="{{ route('help') }}" class="text-light text-decoration-none btn btn-outline-light">
                            <i class="fa-solid fa-question-circle me-2"></i>المساعدة
                        </a>
                        <a href="{{ route('login.form') }}" class="text-light text-decoration-none btn btn-outline-light">
                            <i class="fa-solid fa-sign-in-alt me-2"></i>تسجيل الدخول
                        </a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center mt-4">
                <p class="mb-0 text-muted">&copy; {{ date('Y') }} نظام قسائم غزة. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </section>
</main>

<style>
    /* Additional animations for better UX */
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
    .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
    .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
    .fade-in-up:nth-child(4) { animation-delay: 0.4s; }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Hover effects for cards */
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    /* Button hover effects */
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    /* Stats card hover effects */
    .stats-card {
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stats-card i {
        transition: all 0.3s ease;
    }
    
    .stats-card:hover i {
        transform: scale(1.2);
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .hero-section .display-4 {
            font-size: 2.5rem;
        }
        
        .hero-section .lead {
            font-size: 1.1rem;
        }
        
        .hero-section .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .hero-section .fa-hand-holding-heart {
            font-size: 4rem !important;
        }
        
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .hero-section .display-4 {
            font-size: 2rem;
        }
        
        .hero-section .lead {
            font-size: 1rem;
        }
        
        .hero-section .fa-hand-holding-heart {
            font-size: 3rem !important;
        }
        
        .stats-card {
            margin-bottom: 1rem;
        }
        
        .btn {
            font-size: 0.9rem;
            padding: 0.75rem 1.5rem;
        }
    }
</style>
@endsection
