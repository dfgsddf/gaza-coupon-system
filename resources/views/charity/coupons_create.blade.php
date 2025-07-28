@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3"><i class="fa-solid fa-compass"></i> Campaigns</a>
        <a href="{{ route('charity.coupons') }}" class="d-block text-white mb-3"><i class="fa-solid fa-ticket"></i> Coupons</a>
        <a href="{{ route('charity.requests') }}" class="d-block text-white mb-3"><i class="fa-solid fa-code-pull-request"></i> Requests</a>
        <a href="{{ route('charity.reports') }}" class="d-block text-white mb-3"><i class="fa-solid fa-book-open"></i> Reports</a>
        <a href="{{ route('charity.settings') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gear"></i> Settings</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="submit" class="btn btn-link text-white text-start p-0">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-white mb-3">إضافة كوبون جديد</h2>
            <p class="text-light">إنشاء كوبون جديد للجمعية الخيرية</p>
        </div>
    </div>

    <div class="card bg-dark text-white">
        <div class="card-body">
            <form action="{{ route('charity.coupons.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">كود الكوبون *</label>
                            <input type="text" name="code" id="code" class="form-control" required value="{{ old('code') }}" placeholder="مثل: CHARITY2024">
                            @error('code')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="value" class="form-label">قيمة الكوبون (بالدولار) *</label>
                            <input type="number" name="value" id="value" class="form-control" required min="1" step="0.01" value="{{ old('value') }}" placeholder="مثل: 50">
                            @error('value')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">وصف الكوبون</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="وصف مختصر عن الكوبون وشروط استخدامه">{{ old('description') }}</textarea>
                    @error('description')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="expiry_date" class="form-label">تاريخ انتهاء الصلاحية *</label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control" required value="{{ old('expiry_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('expiry_date')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-save"></i> حفظ الكوبون
                    </button>
                    <a href="{{ route('charity.coupons') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> إلغاء والعودة
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تعيين الحد الأدنى لتاريخ انتهاء الصلاحية (غداً)
    const expiryDateInput = document.getElementById('expiry_date');
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().split('T')[0];
    expiryDateInput.setAttribute('min', minDate);
});
</script>
@endsection 