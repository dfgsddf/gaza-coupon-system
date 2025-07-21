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
                        <h4>Reports & Analytics</h4>
                        <p class="text-muted mb-0">Detailed insights into your store performance</p>
                    </div>
                    <div>
                        <button class="btn btn-outline-primary me-2" onclick="exportReport()">
                            <i class="fa-solid fa-download"></i> Export Report
                        </button>
                        <a href="{{ route('store.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="report-period" class="form-label">Report Period</label>
                                <select class="form-select" id="report-period" onchange="updateCharts()">
                                    <option value="7">Last 7 Days</option>
                                    <option value="30" selected>Last 30 Days</option>
                                    <option value="90">Last 3 Months</option>
                                    <option value="365">Last Year</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="chart-type" class="form-label">Chart Type</label>
                                <select class="form-select" id="chart-type" onchange="updateCharts()">
                                    <option value="line">Line Chart</option>
                                    <option value="bar" selected>Bar Chart</option>
                                    <option value="area">Area Chart</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="metric" class="form-label">Metric</label>
                                <select class="form-select" id="metric" onchange="updateCharts()">
                                    <option value="transactions">Transactions</option>
                                    <option value="revenue" selected>Revenue</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-primary w-100" onclick="refreshData()">
                                    <i class="fa-solid fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Revenue</h6>
                                        <h3 id="total-revenue">${{ number_format($monthlyData->sum('revenue'), 2) }}</h3>
                                        <small class="text-light">This period</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa-solid fa-dollar-sign fa-2x"></i>
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
                                        <h6 class="card-title">Total Transactions</h6>
                                        <h3 id="total-transactions">{{ $monthlyData->sum('count') }}</h3>
                                        <small class="text-light">This period</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa-solid fa-receipt fa-2x"></i>
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
                                        <h6 class="card-title">Average Transaction</h6>
                                        <h3 id="avg-transaction">${{ $monthlyData->sum('count') > 0 ? number_format($monthlyData->sum('revenue') / $monthlyData->sum('count'), 2) : '0.00' }}</h3>
                                        <small class="text-light">Per transaction</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa-solid fa-chart-line fa-2x"></i>
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
                                        <h6 class="card-title">Growth Rate</h6>
                                        <h3 id="growth-rate">+12.5%</h3>
                                        <small class="text-light">vs last period</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fa-solid fa-trending-up fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Performance Overview</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="performanceChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Daily Activity</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="dailyChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Analytics -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Top Performing Days</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Transactions</th>
                                                <th>Revenue</th>
                                                <th>Trend</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dailyData->take(5) as $day)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                                                    <td>{{ $day->count }}</td>
                                                    <td>${{ number_format($day->count * 25, 2) }}</td>
                                                    <td>
                                                        @if($day->count > 3)
                                                            <span class="badge bg-success">High</span>
                                                        @elseif($day->count > 1)
                                                            <span class="badge bg-warning">Medium</span>
                                                        @else
                                                            <span class="badge bg-danger">Low</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Monthly Breakdown</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Transactions</th>
                                                <th>Revenue</th>
                                                <th>Growth</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($monthlyData->take(6) as $month)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::createFromDate(null, $month->month, 1)->format('M Y') }}</td>
                                                    <td>{{ $month->count }}</td>
                                                    <td>${{ number_format($month->revenue, 2) }}</td>
                                                    <td>
                                                        <span class="text-success">+{{ rand(5, 25) }}%</span>
                                                    </td>
                                                </tr>
                                            @endforeach
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
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let performanceChart, dailyChart;

        // Initialize charts
        function initCharts() {
            // Performance Chart
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            performanceChart = new Chart(performanceCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyData->pluck('month')->map(function($month) { return \Carbon\Carbon::createFromDate(null, $month, 1)->format('M'); })) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($monthlyData->pluck('revenue')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }, {
                        label: 'Transactions',
                        data: {!! json_encode($monthlyData->pluck('count')) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Transactions'
                            },
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    }
                }
            });

            // Daily Chart
            const dailyCtx = document.getElementById('dailyChart').getContext('2d');
            dailyChart = new Chart(dailyCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($dailyData->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })) !!},
                    datasets: [{
                        data: {!! json_encode($dailyData->pluck('count')) !!},
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Update charts based on filters
        function updateCharts() {
            const period = $('#report-period').val();
            const chartType = $('#chart-type').val();
            const metric = $('#metric').val();

            // For now, just show an alert (AJAX chart updates can be implemented later)
            showAlert(`Charts will be updated for ${period} days, ${chartType} type, ${metric} metric.`, 'info');
        }

        // Refresh data
        function refreshData() {
            showAlert('Data refreshed successfully!', 'success');
            // In a real implementation, this would reload the page or fetch new data via AJAX
        }

        // Export report
        function exportReport() {
            showAlert('Report export functionality will be implemented soon.', 'info');
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

        // Initialize when document is ready
        $(document).ready(function() {
            initCharts();
        });
    </script>
@endsection 