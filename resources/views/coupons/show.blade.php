@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Coupon Details</h3>
        <div class="card p-4">
            <h4 class="text-primary">${{ $coupon->value }}</h4>
            <p><strong>Store:</strong> {{ $coupon->store_name }}</p>
            <p><strong>Description:</strong> {{ $coupon->description ?? 'N/A' }}</p>
            <p><strong>Expires At:</strong> {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('M d, Y') }}</p>
        </div>
    </div>
@endsection
