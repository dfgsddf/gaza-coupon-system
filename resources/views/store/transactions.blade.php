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
                        <h4>Transaction History</h4>
                        <p class="text-muted mb-0">View and manage all coupon redemptions</p>
                    </div>
                    <a href="{{ route('store.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Filters and Search -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="date-filter" class="form-label">Date Range</label>
                                <select class="form-select" id="date-filter">
                                    <option value="">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status-filter" class="form-label">Status</label>
                                <select class="form-select" id="status-filter">
                                    <option value="">All Status</option>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" class="form-control" id="search" placeholder="Search by beneficiary name or coupon code">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-primary w-100" onclick="filterTransactions()">
                                    <i class="fa-solid fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Transactions</h6>
                                        <h3 id="total-transactions">{{ $transactions->total() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa-solid fa-receipt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Revenue</h6>
                                        <h3 id="total-revenue">${{ number_format($transactions->sum('coupon_value'), 2) }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa-solid fa-dollar-sign fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">This Month</h6>
                                        <h3 id="monthly-transactions">{{ $transactions->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa-solid fa-calendar fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Today</h6>
                                        <h3 id="today-transactions">{{ $transactions->where('created_at', '>=', now()->startOfDay())->count() }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa-solid fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Transaction History</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Beneficiary</th>
                                        <th>Coupon Code</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td>#{{ $transaction->id }}</td>
                                            <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                            <td>{{ $transaction->beneficiary_name }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $transaction->coupon->code ?? 'N/A' }}</span>
                                            </td>
                                            <td class="text-success fw-bold">${{ number_format($transaction->coupon_value, 2) }}</td>
                                            <td>
                                                @if($transaction->status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($transaction->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info" onclick="viewTransactionDetails({{ $transaction->id }})">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="downloadReceipt({{ $transaction->id }})">
                                                    <i class="fa-solid fa-download"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fa-solid fa-inbox fa-3x mb-3"></i>
                                                <p>No transactions found</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($transactions->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="transactionModalBody">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printTransaction()">
                        <i class="fa-solid fa-print"></i> Print
                    </button>
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
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>

    <script>
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Filter transactions
        function filterTransactions() {
            const dateFilter = $('#date-filter').val();
            const statusFilter = $('#status-filter').val();
            const search = $('#search').val();

            // For now, just show an alert (AJAX filtering can be implemented later)
            showAlert('Filtering functionality will be implemented with AJAX.', 'info');
        }

        // View transaction details
        function viewTransactionDetails(transactionId) {
            $.ajax({
                url: `/store/transactions/${transactionId}/details`,
                method: 'GET',
                success: function(response) {
                    $('#transactionModalBody').html(response);
                    $('#transactionModal').modal('show');
                },
                error: function(xhr) {
                    showAlert('Error loading transaction details.', 'danger');
                }
            });
        }

        // Download receipt
        function downloadReceipt(transactionId) {
            showAlert('Receipt download functionality will be implemented soon.', 'info');
        }

        // Print transaction
        function printTransaction() {
            const printContent = $('#transactionModalBody').html();
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Transaction Receipt</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    </head>
                    <body>
                        ${printContent}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        // Show alert
        function showAlert(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 
                             type === 'warning' ? 'alert-warning' : 
                             type === 'info' ? 'alert-info' : 'alert-danger';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            $('body').append(alertHtml);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }

        // Confirm logout
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                $('#logout-form').submit();
            }
        }

        // Event handlers
        $(document).ready(function() {
            // Enter key in search field
            $('#search').keypress(function(e) {
                if (e.which === 13) {
                    filterTransactions();
                }
            });
        });
    </script>
@endsection 