@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4 text-center">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3 active-link"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3"><i class="fa-solid fa-compass me-2"></i> Campaigns</a>
        <a href="{{ route('charity.coupons') }}" class="d-block text-white mb-3"><i class="fa-solid fa-ticket me-2"></i> Coupons</a>
        <a href="{{ route('charity.requests') }}" class="d-block text-white mb-3"><i class="fa-solid fa-code-pull-request me-2"></i> Requests</a>
        <a href="{{ route('charity.reports') }}" class="d-block text-white mb-3"><i class="fa-solid fa-book-open me-2"></i> Reports</a>
        <a href="{{ route('charity.settings') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gear me-2"></i> Settings</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="submit" class="btn btn-link text-white text-start p-0 w-100">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
        @include('charity._dashboard_content')
    </div>
@endsection
