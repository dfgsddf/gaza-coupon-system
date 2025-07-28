@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-8 text-center mx-auto">
                    <div class="animate-in">
                        <h1 class="display-4 fw-bold mb-4">نظام قسائم غزة</h1>
                        <p class="lead mb-4">
                            ربط الجمعيات الخيرية والمتاجر والمستفيدين من خلال منصة آمنة وفعالة لإدارة القسائم والمساعدات الإنسانية.
                            دعم المجتمع من خلال برامج المساعدة الرقمية المتطورة.
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
                    <div class="animate-in">
                        <i class="fa-solid fa-hand-holding-heart fa-8x text-light opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5 animate-in">
                <h2 class="fw-bold">كيف يعمل النظام</h2>
                <p class="lead text-muted">منصتنا تربط جميع الأطراف المعنية في نظام المساعدة</p>
            </div>
            
            <div class="row justify-content-center g-4">
                <div class="col-lg-4 col-md-6 animate-in">
                    <div class="card h-100 stats-card">
                        <div class="card-body text-center p-4">
                            <div class="stats-icon mx-auto">
                                <i class="fa-solid fa-building"></i>
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
                
                <div class="col-lg-4 col-md-6 animate-in">
                    <div class="card h-100 stats-card">
                        <div class="card-body text-center p-4">
                            <div class="stats-icon mx-auto" style="background: linear-gradient(135deg, var(--success-color), #34ce57);">
                                <i class="fa-solid fa-store"></i>
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
                
                <div class="col-lg-4 col-md-6 animate-in">
                    <div class="card h-100 stats-card">
                        <div class="card-body text-center p-4">
                            <div class="stats-icon mx-auto" style="background: linear-gradient(135deg, var(--info-color), #5bc0de);">
                                <i class="fa-solid fa-users"></i>
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
        <div class="container">
            <div class="text-center mb-5 animate-in">
                <h2 class="fw-bold">إحصائيات النظام</h2>
                <p class="lead text-muted">أرقام تعكس تأثيرنا في المجتمع</p>
            </div>
            
            <div class="row justify-content-center g-4">
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="card stats-card text-center">
                        <div class="card-body">
                            <div class="stats-icon mx-auto" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light));">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div class="stats-number">25+</div>
                            <div class="stats-label">جمعية خيرية</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="card stats-card text-center">
                        <div class="card-body">
                            <div class="stats-icon mx-auto" style="background: linear-gradient(135deg, var(--success-color), #34ce57);">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <div class="stats-number">150+</div>
                            <div class="stats-label">متجر مشارك</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="card stats-card text-center">
                        <div class="card-body">
                            <div class="stats-icon mx-auto" style="background: linear-gradient(135deg, var(--info-color), #5bc0de);">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="stats-number">1000+</div>
                            <div class="stats-label">مستفيد</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="card stats-card text-center">
                        <div class="card-body">
                            <div class="stats-icon mx-auto" style="background: linear-gradient(135deg, var(--warning-color), #ffca2c);">
                                <i class="fa-solid fa-ticket-alt"></i>
                            </div>
                            <div class="stats-number">5000+</div>
                            <div class="stats-label">قسيمة ممنوحة</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5 animate-in">
                <h2 class="fw-bold">كيف يعمل النظام</h2>
                <p class="lead text-muted">خطوات بسيطة لربط المحتاجين بالمساعدين</p>
            </div>
            
            <div class="row justify-content-center g-4">
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <span class="fw-bold">1</span>
                            </div>
                            <h5 class="card-title fw-bold">التسجيل</h5>
                            <p class="card-text text-muted">
                                سجل في النظام كجمعية خيرية أو متجر أو مستفيد
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <span class="fw-bold">2</span>
                            </div>
                            <h5 class="card-title fw-bold">إنشاء الحملات</h5>
                            <p class="card-text text-muted">
                                الجمعيات الخيرية تنشئ حملات المساعدة وتحدد المستفيدين
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <span class="fw-bold">3</span>
                            </div>
                            <h5 class="card-title fw-bold">استلام القسائم</h5>
                            <p class="card-text text-muted">
                                المستفيدون يستلمون القسائم الرقمية عبر النظام
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <span class="fw-bold">4</span>
                            </div>
                            <h5 class="card-title fw-bold">الاستخدام</h5>
                            <p class="card-text text-muted">
                                استخدام القسائم في المتاجر المشاركة للحصول على المواد الأساسية
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8 animate-in">
                    <h2 class="fw-bold mb-4">انضم إلينا اليوم</h2>
                    <p class="lead mb-4">
                        ساعد في بناء مجتمع أقوى من خلال المشاركة في نظام قسائم غزة
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fa-solid fa-user-plus me-2"></i>سجل الآن
                        </a>
                        <a href="{{ route('contact.show') }}" class="btn btn-outline-light btn-lg">
                            <i class="fa-solid fa-envelope me-2"></i>اتصل بنا
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
