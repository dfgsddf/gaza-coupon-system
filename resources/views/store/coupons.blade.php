@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 d-none d-lg-block sidebar bg-primary text-white p-4">
                <h5 class="mb-4"><i class="fa-solid fa-store"></i> Store Dashboard</h5>
                <a href="{{ route('store.dashboard') }}" class="d-block text-white mb-3 {{ request()->routeIs('store.dashboard') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="{{ route('store.coupons') }}" class="d-block text-white mb-3 {{ request()->routeIs('store.coupons.*') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-ticket"></i> Coupon Validation
                </a>
                <a href="{{ route('store.transactions') }}" class="d-block text-white mb-3 {{ request()->routeIs('store.transactions.*') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-history"></i> Transaction History
                </a>
                <a href="{{ route('store.reports') }}" class="d-block text-white mb-3 {{ request()->routeIs('store.reports.*') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-chart-bar"></i> Reports
                </a>
                <a href="{{ route('store.settings') }}" class="d-block text-white mb-3 {{ request()->routeIs('store.settings') ? 'active-link' : '' }}">
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
                        <h4>Coupon Validation</h4>
                        <p class="text-muted mb-0">Validate and redeem coupons from beneficiaries</p>
                    </div>
                    <a href="{{ route('store.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Coupon Validation Interface -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fa-solid fa-qrcode"></i> Coupon Validation Interface</h6>
                            </div>
                            <div class="card-body">
                                <div id="validation-alert"></div>
                                
                                <div class="mb-4">
                                    <label for="coupon-code" class="form-label fw-semibold">Coupon Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Enter coupon code or scan QR code" id="coupon-code">
                                        <button class="btn btn-primary" type="button" id="scan-btn">
                                            <i class="fa-solid fa-qrcode"></i> Scan QR
                                        </button>
                                        <button class="btn btn-success" type="button" id="validate-btn">
                                            <i class="fa-solid fa-search"></i> Validate
                                        </button>
                                    </div>
                                </div>

                                <!-- Coupon Details -->
                                <div id="coupon-details" style="display:none;">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Coupon Details</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Beneficiary Name:</strong> <span id="beneficiary-name"></span></p>
                                                    <p><strong>Coupon Value:</strong> <span id="coupon-value" class="text-success fw-bold"></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Expiry Date:</strong> <span id="expiry-date"></span></p>
                                                    <p><strong>Store:</strong> <span id="store-name"></span></p>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <button class="btn btn-success" id="redeem-btn">
                                                    <i class="fa-solid fa-check"></i> Confirm Redemption
                                                </button>
                                                <button class="btn btn-secondary" id="cancel-btn">
                                                    <i class="fa-solid fa-times"></i> Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h6>Today's Validations</h6>
                                <h3 class="text-primary" id="today-validations">0</h3>
                            </div>
                        </div>
                        
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <h6>Today's Revenue</h6>
                                <h3 class="text-success" id="today-revenue">$0.00</h3>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Recent Validations</h6>
                            </div>
                            <div class="card-body">
                                <div id="recent-validations">
                                    <p class="text-muted text-center">No recent validations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validation History -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Recent Validation History</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Coupon Code</th>
                                                <th>Beneficiary</th>
                                                <th>Value</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="validation-history">
                                            <!-- Will be populated by AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .validation-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .validation-item:last-child {
            border-bottom: none;
        }
    </style>

    <script>
        let currentCouponCode = '';
        
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Validate coupon
        function validateCoupon() {
            const couponCode = $('#coupon-code').val().trim();
            
            if (!couponCode) {
                showAlert('Please enter a coupon code.', 'warning');
                return;
            }

            currentCouponCode = couponCode;
            
            $.ajax({
                url: '{{ route("store.validateCoupon") }}',
                method: 'POST',
                data: { code: couponCode },
                success: function(response) {
                    if (response.success) {
                        displayCouponDetails(response.coupon);
                        showAlert('Coupon validated successfully!', 'success');
                    } else {
                        showAlert(response.message, 'danger');
                        hideCouponDetails();
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message 
                        ? xhr.responseJSON.message 
                        : 'Error validating coupon.';
                    showAlert(message, 'danger');
                    hideCouponDetails();
                }
            });
        }

        // Display coupon details
        function displayCouponDetails(coupon) {
            $('#beneficiary-name').text(coupon.beneficiary_name);
            $('#coupon-value').text('$' + coupon.value);
            $('#expiry-date').text(coupon.expiry_date);
            $('#store-name').text(coupon.store_name);
            $('#coupon-details').show();
        }

        // Hide coupon details
        function hideCouponDetails() {
            $('#coupon-details').hide();
            currentCouponCode = '';
        }

        // Redeem coupon
        function redeemCoupon() {
            if (!currentCouponCode) {
                showAlert('No coupon to redeem.', 'warning');
                return;
            }

            $.ajax({
                url: '{{ route("store.redeemCoupon") }}',
                method: 'POST',
                data: { code: currentCouponCode },
                success: function(response) {
                    if (response.success) {
                        showAlert('Coupon redeemed successfully!', 'success');
                        hideCouponDetails();
                        $('#coupon-code').val('');
                        updateStats();
                        addToHistory(response.transaction);
                    } else {
                        showAlert(response.message, 'danger');
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message 
                        ? xhr.responseJSON.message 
                        : 'Error redeeming coupon.';
                    showAlert(message, 'danger');
                }
            });
        }

        // Update stats
        function updateStats() {
            $.ajax({
                url: '{{ route("store.dashboard") }}',
                method: 'GET',
                success: function(response) {
                    $('#today-validations').text($(response).find('#today-transactions-count').text());
                    $('#today-revenue').text($(response).find('#monthly-revenue').text());
                }
            });
        }

        // Add to validation history
        function addToHistory(transaction) {
            const row = `
                <tr>
                    <td>${new Date().toLocaleTimeString()}</td>
                    <td>${currentCouponCode}</td>
                    <td>${transaction.beneficiary_name}</td>
                    <td>$${transaction.coupon_value}</td>
                    <td><span class="badge bg-success">Redeemed</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" onclick="viewTransaction(${transaction.id})">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#validation-history').prepend(row);
        }

        // Show alert
        function showAlert(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 
                             type === 'warning' ? 'alert-warning' : 'alert-danger';
            
            $('#validation-alert').html(`
                <div class="alert ${alertClass} alert-dismissible fade show">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
        }

        // Confirm logout
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                $('#logout-form').submit();
            }
        }

        // Event handlers
        $(document).ready(function() {
            // Validate button click
            $('#validate-btn').click(validateCoupon);
            
            // Enter key in coupon code field
            $('#coupon-code').keypress(function(e) {
                if (e.which === 13) {
                    validateCoupon();
                }
            });
            
            // Redeem button click
            $('#redeem-btn').click(redeemCoupon);
            
            // Cancel button click
            $('#cancel-btn').click(function() {
                hideCouponDetails();
                $('#coupon-code').val('');
            });
            
            // Scan button click (placeholder for QR functionality)
            $('#scan-btn').click(function() {
                showAlert('QR scanning functionality will be implemented soon.', 'info');
            });
            
            // Load initial stats
            updateStats();
        });
    </script>
@endsection 