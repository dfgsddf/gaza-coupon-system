@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h3 class="mb-4">Coupon Details</h3>

            <div class="mb-3">
                <strong>Value:</strong>
                <span class="ms-2">${{ $coupon->value }}</span>
            </div>

            <div class="mb-3">
                <strong>Store Name:</strong>
                <span class="ms-2">{{ $coupon->store_name }}</span>
            </div>

            <div class="mb-3">
                <strong>Expiry Date:</strong>
                <span class="ms-2">{{ \Carbon\Carbon::parse($coupon->expiry_date)->format('F d, Y') }}</span>
            </div>

            <div class="mb-3">
                <strong>Description:</strong>
                <span class="ms-2">{{ $coupon->description ?? 'N/A' }}</span>
            </div>

            <div class="mt-4">
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Back</a>
                {{-- زر اختياري للتحميل أو الطباعة في المستقبل --}}
            </div>
        </div>
    </div>
@endsection
