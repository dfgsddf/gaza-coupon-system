@extends('layouts.app')

@section('title', 'تفاصيل الكوبون')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- العودة للكوبونات -->
            <div class="mb-4">
                <a href="{{ route('beneficiary.coupons.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-right me-2"></i>
                    العودة للكوبونات
                </a>
            </div>
            
            <!-- بطاقة الكوبون -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0">
                        <i class="fas fa-ticket-alt me-3"></i>
                        تفاصيل الكوبون
                    </h2>
                </div>
                
                <div class="card-body p-5">
                    <div class="row">
                        <!-- معلومات الكوبون الأساسية -->
                        <div class="col-md-8">
                            <!-- قيمة الكوبون -->
                            <div class="text-center mb-4 p-4 bg-light rounded">
                                <h1 class="display-3 text-success fw-bold mb-2">
                                    {{ number_format($coupon->value, 0) }}
                                    <small class="fs-2">شيكل</small>
                                </h1>
                                <p class="text-muted fs-5">قيمة الكوبون</p>
                            </div>
                            
                            <!-- كود الكوبون -->
                            <div class="mb-4 text-center">
                                <label class="form-label fw-bold fs-5">كود الكوبون:</label>
                                <div class="mt-2">
                                    <span class="badge bg-dark fs-4 px-4 py-3" style="font-family: 'Courier New', monospace; letter-spacing: 3px;">
                                        {{ $coupon->code }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- معلومات إضافية -->
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <strong class="text-primary">
                                        <i class="fas fa-store me-2"></i>
                                        اسم المتجر:
                                    </strong>
                                    <div class="mt-1 fs-5">{{ $coupon->store_name ?? 'متجر عام' }}</div>
                                </div>
                                
                                <div class="col-sm-6 mb-3">
                                    <strong class="text-danger">
                                        <i class="fas fa-calendar-times me-2"></i>
                                        تاريخ انتهاء الصلاحية:
                                    </strong>
                                    <div class="mt-1 fs-5">{{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d/m/Y') }}</div>
                                </div>
                                
                                <div class="col-sm-6 mb-3">
                                    <strong class="text-info">
                                        <i class="fas fa-clock me-2"></i>
                                        تاريخ الإصدار:
                                    </strong>
                                    <div class="mt-1">{{ \Carbon\Carbon::parse($coupon->created_at)->format('d/m/Y') }}</div>
                                </div>
                                
                                <div class="col-sm-6 mb-3">
                                    <strong class="text-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        الحالة:
                                    </strong>
                                    <div class="mt-1">
                                        @if($coupon->redeemed)
                                            <span class="badge bg-secondary">مستخدم</span>
                                        @elseif(\Carbon\Carbon::parse($coupon->expiry_date)->isPast())
                                            <span class="badge bg-danger">منتهي الصلاحية</span>
                                        @else
                                            <span class="badge bg-success">نشط</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- الوصف -->
                            @if($coupon->description)
                                <div class="mb-4">
                                    <strong class="text-primary">
                                        <i class="fas fa-info-circle me-2"></i>
                                        الوصف:
                                    </strong>
                                    <div class="mt-2 p-3 bg-light rounded">
                                        {{ $coupon->description }}
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- QR Code وأزرار الإجراءات -->
                        <div class="col-md-4">
                            <!-- QR Code مكان محجوز -->
                            <div class="text-center mb-4">
                                <div class="border rounded p-4 bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <div class="text-center">
                                        <i class="fas fa-qrcode fa-4x text-muted mb-2"></i>
                                        <p class="text-muted small">رمز الاستجابة السريعة</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- أزرار الإجراءات -->
                            @if(!$coupon->redeemed && !\Carbon\Carbon::parse($coupon->expiry_date)->isPast())
                                <div class="d-grid gap-3">
                                    <a href="{{ route('beneficiary.coupons.print', $coupon->id) }}" 
                                       class="btn btn-success btn-lg" target="_blank">
                                        <i class="fas fa-print me-2"></i>
                                        طباعة الكوبون
                                    </a>
                                    
                                    <button class="btn btn-outline-primary btn-lg" onclick="shareCoupon()">
                                        <i class="fas fa-share-alt me-2"></i>
                                        مشاركة
                                    </button>
                                    
                                    <button class="btn btn-outline-info btn-lg" onclick="copyCode()">
                                        <i class="fas fa-copy me-2"></i>
                                        نسخ الكود
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <p class="mb-0">
                                        @if($coupon->redeemed)
                                            هذا الكوبون تم استخدامه بالفعل
                                        @else
                                            هذا الكوبون منتهي الصلاحية
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- تعليمات الاستخدام -->
                <div class="card-footer bg-light">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-lightbulb me-2"></i>
                        تعليمات الاستخدام:
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>اطبع الكوبون أو اعرضه على الهاتف</li>
                                <li><i class="fas fa-check text-success me-2"></i>توجه للمتجر المحدد</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>اعرض الكود على البائع</li>
                                <li><i class="fas fa-check text-success me-2"></i>استمتع بقيمة الخصم</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCode() {
    navigator.clipboard.writeText('{{ $coupon->code }}').then(function() {
        alert('تم نسخ كود الكوبون بنجاح!');
    });
}

function shareCoupon() {
    if (navigator.share) {
        navigator.share({
            title: 'كوبون خصم',
            text: 'لديّ كوبون خصم بقيمة {{ number_format($coupon->value, 0) }} شيكل في {{ $coupon->store_name ?? "المتجر" }}',
            url: window.location.href
        });
    } else {
        copyCode();
        alert('تم نسخ معلومات الكوبون للمشاركة!');
    }
}
</script>
@endsection
