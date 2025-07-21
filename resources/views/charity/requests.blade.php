@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3"><i class="fa-solid fa-compass"></i> Campaigns</a>
        <a href="{{ route('charity.requests') }}" class="d-block text-white mb-3 active"><i class="fa-solid fa-code-pull-request"></i> Requests</a>
        <a href="{{ route('charity.reports') }}" class="d-block text-white mb-3"><i class="fa-solid fa-book-open"></i> Reports</a>
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
            <h2 class="text-white mb-0">Charity Requests</h2>
            <button class="btn btn-success" onclick="refreshRequests()">
                <i class="fa fa-sync-alt me-2"></i>Refresh
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Requests</h5>
                        <h3 class="mb-0" id="total-requests">24</h3>
                        <small>Click to refresh</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pending</h5>
                        <h3 class="mb-0" id="pending-requests">8</h3>
                        <small>Click to refresh</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Approved</h5>
                        <h3 class="mb-0" id="approved-requests">12</h3>
                        <small>Click to refresh</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Rejected</h5>
                        <h3 class="mb-0" id="rejected-requests">4</h3>
                        <small>Click to refresh</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card bg-dark text-white mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" id="search-requests" placeholder="Search requests...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select class="form-select" id="filter-status">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select class="form-select" id="filter-type">
                            <option value="">All Types</option>
                            <option value="monthly">Monthly</option>
                            <option value="urgent">Urgent</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-primary w-100" onclick="filterRequests()">
                            <i class="fa fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="card bg-dark text-white">
            <div class="card-header">
                <h5 class="mb-0">Requests List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-striped" id="requests-table">
                        <thead>
                            <tr>
                                <th>Beneficiary</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="requests-tbody">
                            <tr>
                                <td>
                                    <div>
                                        <strong>Ahmed Mohammed</strong>
                                        <br>
                                        <small class="text-muted">ahmed@example.com</small>
                                    </div>
                                </td>
                                <td><span class="badge bg-info">Monthly</span></td>
                                <td>$150.00</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>Dec 15, 2024</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewRequest(1)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="approveRequest(1)">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="rejectRequest(1)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <strong>Fatima Ali</strong>
                                        <br>
                                        <small class="text-muted">fatima@example.com</small>
                                    </div>
                                </td>
                                <td><span class="badge bg-warning">Urgent</span></td>
                                <td>$200.00</td>
                                <td><span class="badge bg-success">Approved</span></td>
                                <td>Dec 14, 2024</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewRequest(2)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="approveRequest(2)" disabled>
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="rejectRequest(2)" disabled>
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <strong>Omar Hassan</strong>
                                        <br>
                                        <small class="text-muted">omar@example.com</small>
                                    </div>
                                </td>
                                <td><span class="badge bg-danger">Emergency</span></td>
                                <td>$300.00</td>
                                <td><span class="badge bg-danger">Rejected</span></td>
                                <td>Dec 13, 2024</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewRequest(3)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="approveRequest(3)" disabled>
                                            <i class="fa fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="rejectRequest(3)" disabled>
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Request Modal -->
    <div class="modal fade" id="viewRequestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Request Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="request-details">
                    <!-- Request details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast for notifications -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="requests-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="requests-toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

<script>
function showRequestsToast(message, type = 'success') {
    const toast = document.getElementById('requests-toast');
    const toastBody = document.getElementById('requests-toast-body');
    
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toastBody.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

function refreshRequests() {
    // In a real application, this would fetch fresh data from the server
    showRequestsToast('Requests list refreshed!');
}

function filterRequests() {
    const search = document.getElementById('search-requests').value;
    const status = document.getElementById('filter-status').value;
    const type = document.getElementById('filter-type').value;
    
    // In a real application, this would send AJAX request to filter data
    showRequestsToast('Requests filtered!');
}

function viewRequest(id) {
    // In a real application, this would load request details via AJAX
    const modal = new bootstrap.Modal(document.getElementById('viewRequestModal'));
    document.getElementById('request-details').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <p><strong>Request ID:</strong> ${id}</p>
                <p><strong>Beneficiary:</strong> User ${id}</p>
                <p><strong>Type:</strong> Monthly</p>
                <p><strong>Amount:</strong> $150.00</p>
            </div>
            <div class="col-md-6">
                <p><strong>Status:</strong> Pending</p>
                <p><strong>Date:</strong> Dec 15, 2024</p>
                <p><strong>Description:</strong> Monthly assistance needed for basic necessities.</p>
            </div>
        </div>
    `;
    modal.show();
}

function approveRequest(id) {
    if (confirm('Are you sure you want to approve this request?')) {
        // In a real application, this would send AJAX request to approve
        showRequestsToast(`Request ${id} approved successfully!`, 'success');
        // Update the row status
        const row = document.querySelector(`tr[data-request-id="${id}"]`);
        if (row) {
            row.querySelector('.badge').className = 'badge bg-success';
            row.querySelector('.badge').textContent = 'Approved';
        }
    }
}

function rejectRequest(id) {
    if (confirm('Are you sure you want to reject this request?')) {
        // In a real application, this would send AJAX request to reject
        showRequestsToast(`Request ${id} rejected!`, 'danger');
        // Update the row status
        const row = document.querySelector(`tr[data-request-id="${id}"]`);
        if (row) {
            row.querySelector('.badge').className = 'badge bg-danger';
            row.querySelector('.badge').textContent = 'Rejected';
        }
    }
}

// Make stats cards clickable for refresh
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', function() {
        if (this.querySelector('small')) {
            showRequestsToast('Statistics refreshed!');
        }
    });
});

// Auto-refresh every 30 seconds
setInterval(function() {
    refreshRequests();
}, 30000);
</script>
@endsection 