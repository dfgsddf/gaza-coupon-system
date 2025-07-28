@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4 text-center">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3 active-link"><i class="fa-solid fa-compass me-2"></i> Campaigns</a>
        <a href="{{ route('charity.requests') }}" class="d-block text-white mb-3"><i class="fa-solid fa-code-pull-request me-2"></i> Requests</a>
        <a href="{{ route('charity.reports') }}" class="d-block text-white mb-3"><i class="fa-solid fa-book-open me-2"></i> Reports</a>
        <a href="{{ route('charity.settings') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gear me-2"></i> Settings</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="button" class="btn btn-link text-white text-start p-0 w-100" onclick="if(confirm('Are you sure?')){$('#logout-form').submit();}">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-2 text-primary fw-bold">
                            <i class="fa-solid fa-compass me-2"></i>
                            Charity Campaigns
                        </h2>
                        <p class="text-muted mb-0">Manage and track your fundraising campaigns</p>
                    </div>
                    <button class="btn btn-primary btn-lg shadow" id="add-campaign-btn">
                        <i class="fa-solid fa-plus me-2"></i> Add New Campaign
                    </button>
                </div>
            </div>
        </div>

        <!-- Campaign Statistics Cards -->
        <div class="row mb-4 g-3">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2 opacity-75">Total Campaigns</h6>
                                <h3 class="mb-0 fw-bold">{{ $campaigns->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-compass fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2 opacity-75">Active Campaigns</h6>
                                <h3 class="mb-0 fw-bold">{{ $campaigns->where('status', 'active')->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-play fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2 opacity-75">Total Raised</h6>
                                <h3 class="mb-0 fw-bold">${{ number_format($campaigns->sum('current_amount'), 2) }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-dollar-sign fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-2 opacity-75">Total Donors</h6>
                                <h3 class="mb-0 fw-bold">{{ $campaigns->sum('donors_count') }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-users fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaigns Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fa-solid fa-list me-2 text-primary"></i>
                                Campaign Management
                            </h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="refreshTable()">
                                    <i class="fa-solid fa-refresh me-1"></i> Refresh
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="exportAllCampaigns()">
                                    <i class="fa-solid fa-download me-1"></i> Export All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="campaigns-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">ID</th>
                                        <th class="border-0 fw-semibold">Campaign Details</th>
                                        <th class="border-0 fw-semibold">Financial Goal</th>
                                        <th class="border-0 fw-semibold">Progress</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                        <th class="border-0 fw-semibold">Created</th>
                                        <th class="border-0 fw-semibold text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($campaigns as $campaign)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">#{{ $campaign->id }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <div>
                                                <h6 class="mb-1 fw-semibold">
                                                    {{ $campaign->name }}
                                                    @if($campaign->is_featured)
                                                        <span class="badge bg-warning ms-2">
                                                            <i class="fa-solid fa-star"></i> Featured
                                                        </span>
                                                    @endif
                                                </h6>
                                                <p class="text-muted mb-0 small">
                                                    @if($campaign->description)
                                                        {{ Str::limit($campaign->description, 80) }}
                                                    @else
                                                        <em>No description available</em>
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="text-center">
                                                <div class="fs-6 fw-bold text-success">${{ number_format($campaign->current_amount, 2) }}</div>
                                                <div class="small text-muted">of ${{ number_format($campaign->goal, 2) }}</div>
                                                <div class="small">
                                                    <i class="fa-solid fa-users me-1"></i>{{ $campaign->donors_count }} donors
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="progress mb-2" style="height: 8px;">
                                                <div class="progress-bar {{ $campaign->progress_percentage >= 100 ? 'bg-success' : ($campaign->progress_percentage >= 75 ? 'bg-info' : 'bg-primary') }}" 
                                                     style="width: {{ min(100, $campaign->progress_percentage) }}%">
                                                </div>
                                            </div>
                                            <div class="text-center small fw-semibold">
                                                {{ number_format($campaign->progress_percentage, 1) }}%
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            @switch($campaign->status)
                                                @case('active')
                                                    <span class="badge bg-success px-3 py-2">
                                                        <i class="fa-solid fa-play me-1"></i>Active
                                                    </span>
                                                    @break
                                                @case('paused')
                                                    <span class="badge bg-warning px-3 py-2">
                                                        <i class="fa-solid fa-pause me-1"></i>Paused
                                                    </span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-info px-3 py-2">
                                                        <i class="fa-solid fa-check-circle me-1"></i>Completed
                                                    </span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger px-3 py-2">
                                                        <i class="fa-solid fa-times-circle me-1"></i>Cancelled
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary px-3 py-2">{{ ucfirst($campaign->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="small">{{ $campaign->created_at->format('M d, Y') }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $campaign->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group-vertical d-grid gap-1" role="group">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="viewCampaign({{ $campaign->id }})"
                                                            title="عرض التفاصيل">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                            onclick="editCampaign({{ $campaign->id }})"
                                                            title="تعديل الحملة">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="viewDonations({{ $campaign->id }})"
                                                            title="عرض التبرعات">
                                                        <i class="fa-solid fa-dollar-sign"></i>
                                                    </button>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                            onclick="exportCampaign({{ $campaign->id }})"
                                                            title="تصدير البيانات">
                                                        <i class="fa-solid fa-download"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteCampaign({{ $campaign->id }})"
                                                            title="حذف الحملة">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa-solid fa-compass fa-3x mb-3 opacity-25"></i>
                                                <h5>No campaigns found</h5>
                                                <p>Start by creating your first campaign</p>
                                                <button class="btn btn-primary" onclick="$('#add-campaign-btn').click()">
                                                    <i class="fa-solid fa-plus me-2"></i>Create Campaign
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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

        <!-- View Campaign Details Modal -->
        <div class="modal fade" id="viewCampaignModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">تفاصيل الحملة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="view-campaign-content">
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

@push('scripts')
<script>
$(document).ready(function() {
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

    // Edit Campaign Form Submit
    $('#edit-campaign-form').on('submit', function(e) {
        e.preventDefault();
        const campaignId = $('#edit-campaign-id').val();
        $.ajax({
            url: `/charity/campaigns/${campaignId}`,
            method: 'PUT',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                $('#editCampaignModal').modal('hide');
                showCampaignToast(res.message, 'success');
                location.reload(); // Reload to show updated campaign
            },
            error: function(xhr) {
                showCampaignToast(xhr.responseJSON?.message || 'خطأ في تحديث الحملة', 'danger');
            }
        });
    });

    // Edit Campaign
    window.editCampaign = function(campaignId) {
        // Load campaign data for editing
        $.ajax({
            url: `/charity/campaigns/${campaignId}/edit`,
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                if (res.success) {
                    const campaign = res.campaign;
                    $('#edit-campaign-id').val(campaign.id);
                    $('#edit-campaign-name').val(campaign.name);
                    $('#edit-campaign-description').val(campaign.description);
                    $('#edit-campaign-goal').val(campaign.goal);
                    $('#edit-campaign-status').val(campaign.status);
                    $('#edit-campaign-start-date').val(campaign.start_date);
                    $('#edit-campaign-end-date').val(campaign.end_date);
                    $('#edit-campaign-featured').prop('checked', campaign.is_featured);
                    $('#editCampaignModal').modal('show');
                } else {
                    showCampaignToast(res.message || 'فشل في تحميل بيانات الحملة', 'danger');
                }
            },
            error: function() {
                showCampaignToast('حدث خطأ أثناء تحميل بيانات الحملة', 'danger');
            }
        });
    }

    // View Campaign Details
    window.viewCampaign = function(campaignId) {
        // Load campaign details for viewing
        $.ajax({
            url: `/charity/campaigns/${campaignId}`,
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                if (res.success) {
                    const campaign = res.campaign;
                    let detailsHtml = `
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">${campaign.name}</h5>
                                <p><strong>الهدف:</strong> $${campaign.goal.toLocaleString()}</p>
                                <p><strong>المبلغ المحصل:</strong> $${campaign.current_amount.toLocaleString()}</p>
                                <p><strong>النسبة:</strong> ${campaign.progress_percentage}%</p>
                                <p><strong>المتبرعين:</strong> ${campaign.donors_count}</p>
                                <p><strong>الحالة:</strong> ${campaign.status}</p>
                                <p><strong>مميزة:</strong> ${campaign.is_featured ? 'نعم' : 'لا'}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>تاريخ البداية:</strong> ${campaign.start_date || 'غير محدد'}</p>
                                <p><strong>تاريخ النهاية:</strong> ${campaign.end_date || 'غير محدد'}</p>
                                <p><strong>تاريخ الإنشاء:</strong> ${campaign.created_at}</p>
                                <p><strong>آخر تحديث:</strong> ${campaign.updated_at}</p>
                            </div>
                        </div>
                    `;
                    
                    if (campaign.description) {
                        detailsHtml += `
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>الوصف:</h6>
                                    <p>${campaign.description}</p>
                                </div>
                            </div>
                        `;
                    }

                    if (campaign.recent_donations && campaign.recent_donations.length > 0) {
                        detailsHtml += `
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>آخر التبرعات:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>المبلغ</th>
                                                    <th>المتبرع</th>
                                                    <th>الرسالة</th>
                                                    <th>التاريخ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                        `;
                        campaign.recent_donations.forEach(donation => {
                            detailsHtml += `
                                <tr>
                                    <td>$${donation.amount}</td>
                                    <td>${donation.donor_name}</td>
                                    <td>${donation.message || '-'}</td>
                                    <td>${donation.created_at}</td>
                                </tr>
                            `;
                        });
                        detailsHtml += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    $('#view-campaign-content').html(detailsHtml);
                    $('#viewCampaignModal').modal('show');
                } else {
                    showCampaignToast(res.message || 'فشل في تحميل تفاصيل الحملة', 'danger');
                }
            },
            error: function() {
                showCampaignToast('حدث خطأ أثناء تحميل تفاصيل الحملة', 'danger');
            }
        });
    }

    // View Donations
    window.viewDonations = function(campaignId) {
        $('#viewDonationsModal').modal('show');
        $('#donations-content').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        $.ajax({
            url: `/charity/campaigns/${campaignId}/donations`,
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                if (res.success) {
                    let donationsHtml = '';
                    if (res.donations && res.donations.length > 0) {
                        donationsHtml = '<div class="row">';
                        res.donations.forEach(donation => {
                            donationsHtml += `
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">${donation.donor_name || 'Anonymous'}</h6>
                                                    <small class="text-muted">${donation.created_at}</small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-success">$${donation.amount}</span>
                                                </div>
                                            </div>
                                            ${donation.message ? `<p class="mt-2 mb-0 small">${donation.message}</p>` : ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        donationsHtml += '</div>';
                    } else {
                        donationsHtml = '<div class="text-center py-4"><p class="text-muted">No donations yet for this campaign.</p></div>';
                    }
                    $('#donations-content').html(donationsHtml);
                } else {
                    $('#donations-content').html('<div class="alert alert-danger">Failed to load donations</div>');
                }
            },
            error: function() {
                $('#donations-content').html('<div class="alert alert-danger">Failed to load donations</div>');
            }
        });
    }

    // Export Campaign Data
    window.exportCampaign = function(campaignId) {
        showCampaignToast('جاري تصدير بيانات الحملة...', 'info');
        
        $.ajax({
            url: `/charity/campaigns/${campaignId}`,
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            success: function(res) {
                if (res.success) {
                    const campaign = res.campaign;
                    
                    // Create CSV content
                    let csvContent = "البيانات,القيم\n";
                    csvContent += `اسم الحملة,"${campaign.name}"\n`;
                    csvContent += `الوصف,"${campaign.description || 'غير محدد'}"\n`;
                    csvContent += `الهدف المالي,${campaign.goal}\n`;
                    csvContent += `المبلغ المحصل,${campaign.current_amount}\n`;
                    csvContent += `النسبة المئوية,${campaign.progress_percentage}%\n`;
                    csvContent += `عدد المتبرعين,${campaign.donors_count}\n`;
                    csvContent += `الحالة,${campaign.status}\n`;
                    csvContent += `مميزة,${campaign.is_featured ? 'نعم' : 'لا'}\n`;
                    csvContent += `تاريخ البداية,${campaign.start_date || 'غير محدد'}\n`;
                    csvContent += `تاريخ النهاية,${campaign.end_date || 'غير محدد'}\n`;
                    csvContent += `تاريخ الإنشاء,${campaign.created_at}\n`;
                    csvContent += `آخر تحديث,${campaign.updated_at}\n`;
                    
                    if (campaign.recent_donations && campaign.recent_donations.length > 0) {
                        csvContent += "\n\nآخر التبرعات:\n";
                        csvContent += "المبلغ,المتبرع,الرسالة,التاريخ\n";
                        campaign.recent_donations.forEach(donation => {
                            csvContent += `${donation.amount},"${donation.donor_name}","${donation.message || ''}",${donation.created_at}\n`;
                        });
                    }
                    
                    // Create and download file
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement("a");
                    const url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", `campaign_${campaignId}_data.csv`);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    showCampaignToast('تم تصدير بيانات الحملة بنجاح!', 'success');
                } else {
                    showCampaignToast(res.message || 'فشل في تصدير بيانات الحملة', 'danger');
                }
            },
            error: function() {
                showCampaignToast('حدث خطأ أثناء تصدير بيانات الحملة', 'danger');
            }
        });
    }

    // Delete Campaign
    window.deleteCampaign = function(campaignId) {
        if (confirm('هل أنت متأكد من حذف هذه الحملة؟ لا يمكن التراجع عن هذا الإجراء.')) {
            $.ajax({
                url: `/charity/campaigns/${campaignId}`,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
                success: function(res) {
                    showCampaignToast(res.message, 'success');
                    location.reload();
                },
                error: function(xhr) {
                    showCampaignToast(xhr.responseJSON?.message || 'خطأ في حذف الحملة', 'danger');
                }
            });
        }
    }
});
</script>
@endpush 