@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3"><i class="fa-solid fa-compass"></i> Campaigns</a>
        <a href="{{ route('charity.coupons') }}" class="d-block text-white mb-3 active"><i class="fa-solid fa-ticket"></i> Coupons</a>
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
            <h2 class="text-white mb-3">إدارة الكوبونات</h2>
            <p class="text-light">مرحباً بك، {{ Auth::user()->name }}! (جمعية خيرية)</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-white">قائمة الكوبونات</h4>
                <a href="{{ route('charity.coupons.create') }}" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> إضافة كوبون جديد
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card bg-dark text-white">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>الكود</th>
                            <th>القيمة</th>
                            <th>الوصف</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td><strong>{{ $coupon->code }}</strong></td>
                                <td>${{ number_format($coupon->value, 2) }}</td>
                                <td>{{ $coupon->description ?? 'لا يوجد وصف' }}</td>
                                <td>{{ $coupon->expiry_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($coupon->redeemed)
                                        <span class="badge bg-success">مستخدم</span>
                                    @elseif($coupon->expiry_date->isPast())
                                        <span class="badge bg-danger">منتهي الصلاحية</span>
                                    @else
                                        <span class="badge bg-warning">غير مستخدم</span>
                                    @endif
                                </td>
                                <td>{{ $coupon->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-ticket fa-3x mb-3 d-block"></i>
                                    لا توجد كوبونات حتى الآن
                                    <br>
                                    <a href="{{ route('charity.coupons.create') }}" class="btn btn-primary mt-2">إضافة أول كوبون</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 