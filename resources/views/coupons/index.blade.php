@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Available Coupons</h3>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Value</th>
                                <th>Store</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td><span class="badge bg-primary">{{ $coupon->code ?? 'N/A' }}</span></td>
                                <td class="fw-bold text-success">${{ number_format($coupon->value, 2) }}</td>
                                <td>{{ $coupon->store_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($coupon->expiry_date)->format('M d, Y') }}</td>
                                <td>
                                    @if(isset($coupon->redeemed) && $coupon->redeemed)
                                        <span class="badge bg-secondary">Redeemed</span>
                                    @elseif(isset($coupon->expiry_date) && \Carbon\Carbon::parse($coupon->expiry_date)->isPast())
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('coupons.show', $coupon->id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-ticket fa-2x mb-2"></i><br>
                                    No coupons available.
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
