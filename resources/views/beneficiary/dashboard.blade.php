@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 d-none d-lg-block sidebar bg-primary text-white p-4">
                <h5 class="mb-4"><i class="fa-solid fa-user"></i> Beneficiary Dashboard</h5>
                <a href="{{ route('beneficiary.dashboard') }}" class="d-block text-white mb-3 {{ request()->routeIs('beneficiary.dashboard') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="{{ route('requests.index') }}" class="d-block text-white mb-3 {{ request()->routeIs('requests.*') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-code-pull-request"></i> Requests
                </a>
                <a href="{{ route('coupons.index') }}" class="d-block text-white mb-3 {{ request()->routeIs('coupons.*') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-ticket"></i> Available Coupon
                </a>
                <a href="{{ route('beneficiary.settings') }}" class="d-block text-white mb-3 {{ request()->routeIs('beneficiary.settings') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-gear"></i> Settings
                </a>
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="button" class="btn btn-link text-white text-start p-0" onclick="confirmLogout()">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 p-4">
                <!-- Topbar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4>Beneficiary Dashboard</h4>
                        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
                    </div>
                    <a href="{{ route('requests.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> New Request
                    </a>
                </div>

                <!-- Stats Cards -->
                <div class="row text-center mb-4">
                    <div class="col-md-3">
                        <div class="bg-white p-4 rounded shadow-sm stats-card" id="requests-card">
                            <h6>Available Requests</h6>
                            <h2 class="text-primary" id="requests-count">{{ $requests->count() }}</h2>
                            <small class="text-muted">Click to refresh</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-white p-4 rounded shadow-sm stats-card" id="coupons-card">
                            <h6>Available Coupons</h6>
                            <h2 class="text-primary" id="coupons-count">{{ $coupons->count() }}</h2>
                            <small class="text-muted">Click to refresh</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Quick Actions</h6>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('requests.create') }}" class="btn btn-outline-primary w-100">
                                            <i class="fa-solid fa-plus"></i> New Request
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('requests.index') }}" class="btn btn-outline-info w-100">
                                            <i class="fa-solid fa-list"></i> View All Requests
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('coupons.index') }}" class="btn btn-outline-success w-100">
                                            <i class="fa-solid fa-ticket"></i> View All Coupons
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <button class="btn btn-outline-secondary w-100" onclick="refreshAll()">
                                            <i class="fa-solid fa-refresh"></i> Refresh All
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requests Table -->
                <div class="card p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Recent Requests</h6>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshRequests()">
                            <i class="fa-solid fa-refresh"></i> Refresh
                        </button>
                    </div>
                    <div id="requests-table-container">
                        <table class="table table-bordered mt-2">
                            <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="requests-tbody">
                            @forelse($requests as $req)
                                <tr class="request-row" data-request-id="{{ $req->id }}">
                                    <td>{{ $req->id }}</td>
                                    <td>{{ ucfirst($req->type) }}</td>
                                    <td>{{ $req->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge status-badge
                                            @if($req->status == 'approved') bg-success
                                            @elseif($req->status == 'rejected') bg-danger
                                            @else bg-warning text-dark @endif">
                                            {{ ucfirst($req->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewRequestDetails({{ $req->id }})">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No requests found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Active Coupons -->
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Active Coupons</h6>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshCoupons()">
                            <i class="fa-solid fa-refresh"></i> Refresh
                        </button>
                    </div>
                    <div id="coupons-container">
                        <div class="row text-center mt-3">
                            @forelse($coupons as $coupon)
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 bg-light coupon-card" data-coupon-id="{{ $coupon->id }}" onclick="viewCouponDetails({{ $coupon->id }})">
                                        <h4 class="text-success">${{ $coupon->value }}</h4>
                                        <small>
                                            {{ $coupon->description ?? 'Coupon' }}<br>
                                            Exp: {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('m/d/Y') }}<br>
                                            Store: {{ $coupon->store_name }}
                                        </small>
                                        <div class="mt-2">
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-muted">No active coupons.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Details Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="request-modal-body">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Coupon Details Modal -->
    <div class="modal fade" id="couponModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Coupon Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="coupon-modal-body">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .sidebar a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .sidebar a:hover, .sidebar a.active-link {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .stats-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .request-row {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .request-row:hover {
            background-color: #f8f9fa;
        }
        
        .coupon-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .coupon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: #007bff;
        }
        
        .status-badge {
            transition: all 0.3s ease;
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>

    <script>
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Refresh requests data
        function refreshRequests() {
            $('#requests-table-container').addClass('loading');
            
            $.ajax({
                url: '{{ route("beneficiary.dashboard") }}',
                method: 'GET',
                success: function(response) {
                    // Update the requests table
                    $('#requests-tbody').html($(response).find('#requests-tbody').html());
                    $('#requests-count').text($(response).find('#requests-count').text());
                    
                    // Show success message
                    showNotification('Requests refreshed successfully!', 'success');
                },
                error: function() {
                    showNotification('Failed to refresh requests.', 'error');
                },
                complete: function() {
                    $('#requests-table-container').removeClass('loading');
                }
            });
        }

        // Refresh coupons data
        function refreshCoupons() {
            $('#coupons-container').addClass('loading');
            
            $.ajax({
                url: '{{ route("beneficiary.dashboard") }}',
                method: 'GET',
                success: function(response) {
                    // Update the coupons container
                    $('#coupons-container').html($(response).find('#coupons-container').html());
                    $('#coupons-count').text($(response).find('#coupons-count').text());
                    
                    // Show success message
                    showNotification('Coupons refreshed successfully!', 'success');
                },
                error: function() {
                    showNotification('Failed to refresh coupons.', 'error');
                },
                complete: function() {
                    $('#coupons-container').removeClass('loading');
                }
            });
        }

        // View request details
        function viewRequestDetails(requestId) {
            $('#request-modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
            $('#requestModal').modal('show');
            
            $.ajax({
                url: `/requests/${requestId}/details`,
                method: 'GET',
                success: function(response) {
                    $('#request-modal-body').html(response);
                },
                error: function() {
                    $('#request-modal-body').html('<div class="alert alert-danger">Failed to load request details.</div>');
                }
            });
        }

        // View coupon details
        function viewCouponDetails(couponId) {
            $('#coupon-modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
            $('#couponModal').modal('show');
            
            $.ajax({
                url: `/coupons/${couponId}/details`,
                method: 'GET',
                success: function(response) {
                    $('#coupon-modal-body').html(response);
                },
                error: function() {
                    $('#coupon-modal-body').html('<div class="alert alert-danger">Failed to load coupon details.</div>');
                }
            });
        }

        // Refresh all data
        function refreshAll() {
            refreshRequests();
            refreshCoupons();
            showNotification('All data refreshed successfully!', 'success');
        }

        // Confirm logout
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                $('#logout-form').submit();
            }
        }

        // Show notification
        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const notification = $(`
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            $('body').append(notification);
            
            setTimeout(function() {
                notification.alert('close');
            }, 3000);
        }

        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            $.ajax({
                url: '{{ route("beneficiary.dashboard") }}',
                method: 'GET',
                success: function(response) {
                    const newRequestsCount = $(response).find('#requests-count').text();
                    const newCouponsCount = $(response).find('#coupons-count').text();
                    
                    // Only show notification if counts changed
                    if (newRequestsCount !== $('#requests-count').text()) {
                        $('#requests-count').text(newRequestsCount);
                        showNotification('New request status update!', 'info');
                    }
                    
                    if (newCouponsCount !== $('#coupons-count').text()) {
                        $('#coupons-count').text(newCouponsCount);
                        showNotification('New coupon available!', 'success');
                    }
                }
            });
        }, 30000);

        // Click handlers for stats cards
        $(document).ready(function() {
            $('#requests-card').click(function() {
                refreshRequests();
            });
            
            $('#coupons-card').click(function() {
                refreshCoupons();
            });
            
            // Add click handler for request rows
            $(document).on('click', '.request-row', function() {
                const requestId = $(this).data('request-id');
                viewRequestDetails(requestId);
            });
        });
    </script>
@endsection
