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
                    <button type="submit" class="btn btn-link text-white text-start p-0">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 p-4">
                <!-- Topbar -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4>Store Dashboard</h4>
                        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}! ({{ Auth::user()->store_name ?? 'Store' }})</p>
                    </div>
                    <a href="{{ route('store.coupons') }}" class="btn btn-primary">
                        <i class="fa-solid fa-qrcode"></i> Validate Coupon
                    </a>
                </div>

                <!-- Stats Cards -->
                <div class="row text-center mb-4">
                    <div class="col-md-3">
                        <div class="bg-white p-4 rounded shadow-sm stats-card" id="today-transactions-card">
                            <h6>Today's Transactions</h6>
                            <h2 class="text-primary" id="today-transactions-count">{{ $todayTransactions }}</h2>
                            <small class="text-muted">Click to refresh</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-white p-4 rounded shadow-sm stats-card" id="monthly-revenue-card">
                            <h6>Monthly Revenue</h6>
                            <h2 class="text-success" id="monthly-revenue">${{ number_format($monthlyRevenue, 2) }}</h2>
                            <small class="text-muted">Click to refresh</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-white p-4 rounded shadow-sm stats-card" id="total-coupons-card">
                            <h6>Total Coupons Redeemed</h6>
                            <h2 class="text-info" id="total-coupons-count">{{ $totalCoupons }}</h2>
                            <small class="text-muted">Click to refresh</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-white p-4 rounded shadow-sm stats-card" id="pending-coupons-card">
                            <h6>Pending Coupons</h6>
                            <h2 class="text-warning" id="pending-coupons-count">{{ $pendingCoupons }}</h2>
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
                                        <a href="{{ route('store.coupons') }}" class="btn btn-outline-primary w-100">
                                            <i class="fa-solid fa-qrcode"></i> Validate Coupon
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('store.transactions') }}" class="btn btn-outline-info w-100">
                                            <i class="fa-solid fa-history"></i> View Transactions
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('store.reports') }}" class="btn btn-outline-success w-100">
                                            <i class="fa-solid fa-chart-bar"></i> Generate Report
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

                <!-- Recent Transactions -->
                <div class="card p-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Recent Transactions</h6>
                        <button class="btn btn-sm btn-outline-primary" onclick="refreshTransactions()">
                            <i class="fa-solid fa-refresh"></i> Refresh
                        </button>
                    </div>
                    <div id="transactions-table-container">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Beneficiary</th>
                                    <th>Coupon Value</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="transactions-tbody">
                                @forelse($recentTransactions as $transaction)
                                    <tr class="transaction-row" data-transaction-id="{{ $transaction->id }}">
                                        <td>#{{ $transaction->id }}</td>
                                        <td>{{ $transaction->beneficiary_name }}</td>
                                        <td>${{ number_format($transaction->coupon_value, 2) }}</td>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-success">Completed</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewTransactionDetails({{ $transaction->id }})">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No transactions found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Store Performance -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Daily Performance</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="dailyChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Top Beneficiaries</h6>
                            </div>
                            <div class="card-body">
                                <div id="top-beneficiaries">
                                    @forelse($topBeneficiaries as $beneficiary)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span>{{ $beneficiary->name }}</span>
                                            <span class="badge bg-primary">{{ $beneficiary->total_coupons }} coupons</span>
                                        </div>
                                    @empty
                                        <p class="text-muted">No data available.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="transaction-modal-body">
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
        
        .transaction-row {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .transaction-row:hover {
            background-color: #f8f9fa;
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Refresh transactions data
        function refreshTransactions() {
            $('#transactions-table-container').addClass('loading');
            
            $.ajax({
                url: '{{ route("store.dashboard") }}',
                method: 'GET',
                success: function(response) {
                    // Update the transactions table
                    $('#transactions-tbody').html($(response).find('#transactions-tbody').html());
                    
                    // Show success message
                    showNotification('Transactions refreshed successfully!', 'success');
                },
                error: function() {
                    showNotification('Failed to refresh transactions.', 'error');
                },
                complete: function() {
                    $('#transactions-table-container').removeClass('loading');
                }
            });
        }

        // Refresh all data
        function refreshAll() {
            refreshTransactions();
            refreshStats();
            showNotification('All data refreshed successfully!', 'success');
        }

        // Refresh stats
        function refreshStats() {
            $.ajax({
                url: '{{ route("store.dashboard") }}',
                method: 'GET',
                success: function(response) {
                    $('#today-transactions-count').text($(response).find('#today-transactions-count').text());
                    $('#monthly-revenue').text($(response).find('#monthly-revenue').text());
                    $('#total-coupons-count').text($(response).find('#total-coupons-count').text());
                    $('#pending-coupons-count').text($(response).find('#pending-coupons-count').text());
                }
            });
        }

        // View transaction details
        function viewTransactionDetails(transactionId) {
            $('#transaction-modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
            $('#transactionModal').modal('show');
            
            $.ajax({
                url: `/store/transactions/${transactionId}/details`,
                method: 'GET',
                success: function(response) {
                    $('#transaction-modal-body').html(response);
                },
                error: function() {
                    $('#transaction-modal-body').html('<div class="alert alert-danger">Failed to load transaction details.</div>');
                }
            });
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
            refreshStats();
        }, 30000);

        // Click handlers for stats cards
        $(document).ready(function() {
            $('#today-transactions-card').click(function() {
                refreshTransactions();
            });
            
            $('#monthly-revenue-card').click(function() {
                refreshStats();
            });
            
            $('#total-coupons-card').click(function() {
                refreshStats();
            });
            
            $('#pending-coupons-card').click(function() {
                refreshStats();
            });
            
            // Add click handler for transaction rows
            $(document).on('click', '.transaction-row', function() {
                const transactionId = $(this).data('transaction-id');
                viewTransactionDetails(transactionId);
            });

            // Initialize daily performance chart
            const ctx = document.getElementById('dailyChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Transactions',
                        data: [12, 19, 15, 25, 22, 30, 28],
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
