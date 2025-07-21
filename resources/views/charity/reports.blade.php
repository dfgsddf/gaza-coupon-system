@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3"><i class="fa-solid fa-compass"></i> Campaigns</a>
        <a href="{{ route('charity.requests') }}" class="d-block text-white mb-3"><i class="fa-solid fa-code-pull-request"></i> Requests</a>
        <a href="{{ route('charity.reports') }}" class="d-block text-white mb-3 active-link"><i class="fa-solid fa-book-open"></i> Reports</a>
        <a href="{{ route('charity.settings') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gear"></i> Settings</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="button" class="btn btn-link text-white text-start p-0" onclick="if(confirm('Are you sure?')){$('#logout-form').submit();}">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Charity Reports & Analytics</h2>
            <button class="btn btn-primary" id="generate-report-btn">
                <i class="fa-solid fa-plus"></i> Generate Report
            </button>
        </div>

        <!-- Report Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Reports</h6>
                                <h3 class="mb-0">{{ $totalReports }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-file-alt fa-2x"></i>
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
                                <h6 class="card-title">Campaign Performance</h6>
                                <h3 class="mb-0">{{ $campaignPerformance->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-chart-line fa-2x"></i>
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
                                <h6 class="card-title">Donation Trends</h6>
                                <h3 class="mb-0">{{ $donationTrends->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-chart-bar fa-2x"></i>
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
                                <h6 class="card-title">Recent Reports</h6>
                                <h3 class="mb-0">{{ $recentReports->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Performance Chart -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Campaign Performance Overview</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="campaignPerformanceChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Donation Trends</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="donationTrendsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Performance Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Campaign Performance Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Campaign Name</th>
                                <th>Goal</th>
                                <th>Raised</th>
                                <th>Progress</th>
                                <th>Donors</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaignPerformance as $campaign)
                            <tr>
                                <td><strong>{{ $campaign['name'] }}</strong></td>
                                <td>${{ number_format($campaign['goal'], 2) }}</td>
                                <td>${{ number_format($campaign['raised'], 2) }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $campaign['progress'] >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                             style="width: {{ min(100, $campaign['progress']) }}%">
                                            {{ number_format($campaign['progress'], 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $campaign['donors_count'] }}</td>
                                <td>
                                    @if($campaign['progress'] >= 100)
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($campaign['progress'] >= 75)
                                        <span class="badge bg-info">Near Goal</span>
                                    @elseif($campaign['progress'] >= 50)
                                        <span class="badge bg-warning">Halfway</span>
                                    @else
                                        <span class="badge bg-secondary">In Progress</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Reports</h5>
            </div>
            <div class="card-body">
                @if($recentReports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Report Title</th>
                                    <th>Type</th>
                                    <th>Generated Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentReports as $report)
                                <tr>
                                    <td><strong>{{ $report->title }}</strong></td>
                                    <td>
                                        @switch($report->report_type)
                                            @case('campaign_summary')
                                                <span class="badge bg-primary">Campaign Summary</span>
                                                @break
                                            @case('donation_analysis')
                                                <span class="badge bg-success">Donation Analysis</span>
                                                @break
                                            @case('financial_report')
                                                <span class="badge bg-info">Financial Report</span>
                                                @break
                                            @case('request_summary')
                                                <span class="badge bg-warning">Request Summary</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $report->report_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($report->isExported())
                                            <span class="badge bg-success">Exported</span>
                                        @elseif($report->isArchived())
                                            <span class="badge bg-secondary">Archived</span>
                                        @else
                                            <span class="badge bg-primary">Generated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewReport({{ $report->id }})">
                                                <i class="fa-solid fa-eye"></i> View
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="exportReport({{ $report->id }})">
                                                <i class="fa-solid fa-download"></i> Export
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa-solid fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No reports generated yet</h5>
                        <p class="text-muted">Generate your first report to see analytics and insights.</p>
                        <button class="btn btn-primary" id="generate-first-report-btn">
                            <i class="fa-solid fa-plus"></i> Generate First Report
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Generate Report Modal -->
        <div class="modal fade" id="generateReportModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="generate-report-form">
                        <div class="modal-header">
                            <h5 class="modal-title">Generate New Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="report-title" class="form-label">Report Title *</label>
                                        <input type="text" class="form-control" id="report-title" name="title" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="report-type" class="form-label">Report Type *</label>
                                        <select class="form-control" id="report-type" name="report_type" required>
                                            <option value="">Select Report Type</option>
                                            <option value="campaign_summary">Campaign Summary</option>
                                            <option value="donation_analysis">Donation Analysis</option>
                                            <option value="financial_report">Financial Report</option>
                                            <option value="request_summary">Request Summary</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="report-description" class="form-label">Description</label>
                                <textarea class="form-control" id="report-description" name="description" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="report-start-date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="report-start-date" name="start_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="report-end-date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="report-end-date" name="end_date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-cog fa-spin d-none" id="generate-spinner"></i>
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Toasts -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
            <div id="reports-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="reports-toast-body"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function showReportsToast(msg, type = 'success') {
    const toast = $('#reports-toast');
    toast.removeClass('text-bg-success text-bg-danger text-bg-info').addClass('text-bg-' + type);
    $('#reports-toast-body').text(msg);
    toast.toast('show');
}

// Generate Report
$('#generate-report-btn, #generate-first-report-btn').on('click', function() {
    $('#generateReportModal').modal('show');
});

$('#generate-report-form').on('submit', function(e) {
    e.preventDefault();
    
    const spinner = $('#generate-spinner');
    const submitBtn = $(this).find('button[type="submit"]');
    
    spinner.removeClass('d-none');
    submitBtn.prop('disabled', true);
    
    $.ajax({
        url: "{{ route('charity.reports.generate') }}",
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
        success: function(res) {
            $('#generateReportModal').modal('hide');
            showReportsToast(res.message, 'success');
            setTimeout(() => location.reload(), 1500);
        },
        error: function(xhr) {
            showReportsToast(xhr.responseJSON?.message || 'Error generating report', 'danger');
        },
        complete: function() {
            spinner.addClass('d-none');
            submitBtn.prop('disabled', false);
        }
    });
});

// View Report
function viewReport(reportId) {
    showReportsToast('View report functionality will be implemented in the next update', 'info');
}

// Export Report
function exportReport(reportId) {
    $.ajax({
        url: `/charity/reports/${reportId}/export`,
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
        success: function(res) {
            showReportsToast(res.message, 'success');
        },
        error: function(xhr) {
            showReportsToast(xhr.responseJSON?.message || 'Error exporting report', 'danger');
        }
    });
}

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Campaign Performance Chart
    const campaignCtx = document.getElementById('campaignPerformanceChart').getContext('2d');
    new Chart(campaignCtx, {
        type: 'bar',
        data: {
            labels: @json($campaignPerformance->pluck('name')),
            datasets: [{
                label: 'Goal ($)',
                data: @json($campaignPerformance->pluck('goal')),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Raised ($)',
                data: @json($campaignPerformance->pluck('raised')),
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Donation Trends Chart
    const donationCtx = document.getElementById('donationTrendsChart').getContext('2d');
    new Chart(donationCtx, {
        type: 'line',
        data: {
            labels: @json($donationTrends->pluck('month')),
            datasets: [{
                label: 'Total Donations ($)',
                data: @json($donationTrends->pluck('total')),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
});
</script> 