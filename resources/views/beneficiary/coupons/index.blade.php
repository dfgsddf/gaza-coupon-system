@extends('layouts.app')

@section('title', 'الكوبونات المتاحة')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container py-5">
        <div class="text-center text-white mb-5">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-ticket-alt me-3"></i>
                كوبوناتي المتاحة
            </h1>
            <p class="lead">استعرض واستخدم الكوبونات المخصصة لك</p>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-success text-white shadow">
                    <div class="card-body">
                        <i class="fas fa-ticket-alt fa-2x mb-2"></i>
                        <h3>{{ count($coupons) }}</h3>
                        <p class="mb-0">كوبونات متاحة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-info text-white shadow">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h3>{{ $usedCoupons }}</h3>
                        <p class="mb-0">كوبونات مستخدمة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center bg-warning text-white shadow">
                    <div class="card-body">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <h3>{{ $expiredCoupons }}</h3>
                        <p class="mb-0">كوبونات منتهية</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- الكوبونات المتاحة -->
        <div class="row">
            @forelse($coupons as $coupon)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-lg border-0 coupon-card" style="transform: scale(1); transition: all 0.3s ease;">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="mb-0">
                                <i class="fas fa-store me-2"></i>
                                {{ $coupon->store_name ?? 'متجر عام' }}
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <!-- قيمة الكوبون -->
                            <div class="mb-3">
                                <div class="display-4 text-success fw-bold">
                                    {{ number_format($coupon->value, 0) }}
                                    <small class="fs-5">شيكل</small>
                                </div>
                            </div>

                            <!-- كود الكوبون -->
                            <div class="mb-3">
                                <span class="badge bg-dark fs-6 px-3 py-2">
                                    {{ $coupon->code }}
                                </span>
                            </div>

                            <!-- تاريخ انتهاء الصلاحية -->
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    ينتهي في: {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d/m/Y') }}
                                </small>
                            </div>

                            <!-- الوصف -->
                            @if($coupon->description)
                                <div class="mb-3">
                                    <p class="text-muted small">{{ $coupon->description }}</p>
                                </div>
                            @endif

                            <!-- الأزرار -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('beneficiary.coupons.show', $coupon->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>
                                    عرض التفاصيل
                                </a>
                                <a href="{{ route('beneficiary.coupons.print', $coupon->id) }}" 
                                   class="btn btn-success" target="_blank">
                                    <i class="fas fa-print me-2"></i>
                                    طباعة الكوبون
                                </a>
                            </div>
                        </div>
                        <div class="card-footer bg-light text-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                تم إنشاؤه: {{ \Carbon\Carbon::parse($coupon->created_at)->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card bg-light text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-ticket-alt fa-4x text-muted mb-4"></i>
                            <h3 class="text-muted">لا توجد كوبونات متاحة حالياً</h3>
                            <p class="text-muted">ستظهر الكوبونات الجديدة هنا عند توفرها لك</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- تعليمات الاستخدام -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-white shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            كيفية استخدام الكوبونات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary rounded-circle p-2">1</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6>اطبع الكوبون</h6>
                                        <p class="small text-muted">اضغط على "طباعة الكوبون" للحصول على نسخة مطبوعة</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary rounded-circle p-2">2</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6>اذهب للمتجر</h6>
                                        <p class="small text-muted">زر المتجر المحدد واعرض الكوبون المطبوع</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary rounded-circle p-2">3</span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6>استمتع بالخصم</h6>
                                        <p class="small text-muted">احصل على قيمة الكوبون من مشترياتك</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.coupon-card:hover {
    transform: scale(1.02) !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.display-4 {
    font-family: 'Arial Black', sans-serif;
}

.card {
    border-radius: 15px;
}

.badge {
    letter-spacing: 2px;
    font-family: 'Courier New', monospace;
}
</style>
@endsection 