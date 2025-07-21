@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3 active-link"><i class="fa-solid fa-compass"></i> Campaigns</a>
        <a href="{{ route('charity.requests') }}" class="d-block text-white mb-3"><i class="fa-solid fa-code-pull-request"></i> Requests</a>
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
            <h2>Charity Campaigns</h2>
            <button class="btn btn-primary" id="add-campaign-btn">
                <i class="fa-solid fa-plus"></i> Add Campaign
            </button>
        </div>

        <!-- Campaign Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Campaigns</h6>
                                <h3 class="mb-0">{{ $campaigns->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-compass fa-2x"></i>
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
                                <h6 class="card-title">Active Campaigns</h6>
                                <h3 class="mb-0">{{ $campaigns->where('status', 'active')->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-play fa-2x"></i>
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
                                <h6 class="card-title">Total Raised</h6>
                                <h3 class="mb-0">${{ number_format($campaigns->sum('current_amount'), 2) }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-dollar-sign fa-2x"></i>
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
                                <h6 class="card-title">Total Donors</h6>
                                <h3 class="mb-0">{{ $campaigns->sum('donors_count') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaigns Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Campaign Management</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="campaigns-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Goal</th>
                                <th>Raised</th>
                                <th>Progress</th>
                                <th>Donors</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->id }}</td>
                                <td>
                                    <strong>{{ $campaign->name }}</strong>
                                    @if($campaign->is_featured)
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    @if($campaign->description)
                                        {{ Str::limit($campaign->description, 50) }}
                                    @else
                                        <span class="text-muted">No description</span>
                                    @endif
                                </td>
                                <td>${{ number_format($campaign->goal, 2) }}</td>
                                <td>${{ number_format($campaign->current_amount, 2) }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $campaign->progress_percentage >= 100 ? 'bg-success' : 'bg-primary' }}" 
                                             style="width: {{ min(100, $campaign->progress_percentage) }}%">
                                            {{ number_format($campaign->progress_percentage, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $campaign->donors_count }}</td>
                                <td>
                                    @switch($campaign->status)
                                        @case('active')
                                            <span class="badge bg-success">Active</span>
                                            @break
                                        @case('paused')
                                            <span class="badge bg-warning">Paused</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-info">Completed</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($campaign->status) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if($campaign->is_featured)
                                        <span class="badge bg-warning">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>{{ $campaign->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewCampaign({{ $campaign->id }})">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="editCampaign({{ $campaign->id }})">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="viewDonations({{ $campaign->id }})">
                                            <i class="fa-solid fa-hand-holding-heart"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteCampaign({{ $campaign->id }})">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Campaign Modal -->
        <div class="modal fade" id="addCampaignModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="add-campaign-form">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Campaign</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="campaign-name" class="form-label">Campaign Name *</label>
                                        <input type="text" class="form-control" id="campaign-name" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="campaign-goal" class="form-label">Financial Goal *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="campaign-goal" name="goal" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="campaign-description" class="form-label">Description</label>
                                <textarea class="form-control" id="campaign-description" name="description" rows="3" maxlength="1000"></textarea>
                                <div class="form-text">Brief description of the campaign (optional)</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="campaign-start-date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="campaign-start-date" name="start_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="campaign-end-date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="campaign-end-date" name="end_date">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="campaign-featured" name="is_featured" value="1">
                                    <label class="form-check-label" for="campaign-featured">
                                        Mark as Featured Campaign
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Campaign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Campaign Modal -->
        <div class="modal fade" id="editCampaignModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="edit-campaign-form">
                        <input type="hidden" id="edit-campaign-id" name="campaign_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Campaign</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit-campaign-name" class="form-label">Campaign Name *</label>
                                        <input type="text" class="form-control" id="edit-campaign-name" name="name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit-campaign-goal" class="form-label">Financial Goal *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control" id="edit-campaign-goal" name="goal" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit-campaign-description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit-campaign-description" name="description" rows="3" maxlength="1000"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit-campaign-status" class="form-label">Status</label>
                                        <select class="form-control" id="edit-campaign-status" name="status">
                                            <option value="active">Active</option>
                                            <option value="paused">Paused</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit-campaign-start-date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="edit-campaign-start-date" name="start_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit-campaign-end-date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="edit-campaign-end-date" name="end_date">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit-campaign-featured" name="is_featured" value="1">
                                    <label class="form-check-label" for="edit-campaign-featured">
                                        Mark as Featured Campaign
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Campaign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Donations Modal -->
        <div class="modal fade" id="viewDonationsModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Campaign Donations</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="donations-content">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toasts -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
            <div id="campaigns-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="campaigns-toast-body"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
function showCampaignToast(msg, type = 'success') {
    const toast = $('#campaigns-toast');
    toast.removeClass('text-bg-success text-bg-danger text-bg-info').addClass('text-bg-' + type);
    $('#campaigns-toast-body').text(msg);
    toast.toast('show');
}

// Add Campaign
$('#add-campaign-btn').on('click', function() {
    $('#addCampaignModal').modal('show');
});

$('#add-campaign-form').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: "{{ route('charity.campaigns.store') }}",
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
        success: function(res) {
            $('#addCampaignModal').modal('hide');
            showCampaignToast(res.message, 'success');
            location.reload(); // Reload to show new campaign
        },
        error: function(xhr) {
            showCampaignToast(xhr.responseJSON?.message || 'Error creating campaign', 'danger');
        }
    });
});

// Edit Campaign
function editCampaign(campaignId) {
    // For now, just show a message - you can implement full edit functionality
    showCampaignToast('Edit functionality will be implemented in the next update', 'info');
}

// View Campaign Details
function viewCampaign(campaignId) {
    showCampaignToast('View details functionality will be implemented in the next update', 'info');
}

// View Donations
function viewDonations(campaignId) {
    $('#viewDonationsModal').modal('show');
    $('#donations-content').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    
    $.ajax({
        url: `/charity/campaigns/${campaignId}/donations`,
        method: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
        success: function(res) {
            if (res.success) {
                let html = '<div class="table-responsive"><table class="table table-striped">';
                html += '<thead><tr><th>Donor</th><th>Amount</th><th>Payment Method</th><th>Status</th><th>Date</th></tr></thead><tbody>';
                
                res.donations.data.forEach(function(donation) {
                    html += `<tr>
                        <td>${donation.donor_display_name}</td>
                        <td>$${parseFloat(donation.amount).toFixed(2)}</td>
                        <td>${donation.payment_method}</td>
                        <td><span class="badge bg-${donation.payment_status === 'completed' ? 'success' : 'warning'}">${donation.payment_status}</span></td>
                        <td>${new Date(donation.created_at).toLocaleDateString()}</td>
                    </tr>`;
                });
                
                html += '</tbody></table></div>';
                $('#donations-content').html(html);
            } else {
                $('#donations-content').html('<div class="alert alert-info">No donations found for this campaign.</div>');
            }
        },
        error: function() {
            $('#donations-content').html('<div class="alert alert-danger">Error loading donations.</div>');
        }
    });
}

// Delete Campaign
function deleteCampaign(campaignId) {
    if (confirm('Are you sure you want to delete this campaign? This action cannot be undone.')) {
        $.ajax({
            url: `/charity/campaigns/${campaignId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                showCampaignToast(res.message, 'success');
                location.reload();
            },
            error: function(xhr) {
                showCampaignToast(xhr.responseJSON?.message || 'Error deleting campaign', 'danger');
            }
        });
    }
}
</script> 