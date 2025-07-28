<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-dark mb-3">Charity Dashboard</h2>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}! (Charity)</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Campaigns</h5>
                    <h3 class="mb-0" id="campaigns-count">{{ $campaignsCount ?? 0 }}</h3>
                    <small>Click to refresh</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Active Campaigns</h5>
                    <h3 class="mb-0" id="active-campaigns-count">{{ $activeCampaignsCount ?? 0 }}</h3>
                    <small>Click to refresh</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Donations</h5>
                    <h3 class="mb-0" id="total-donations">${{ number_format($totalDonations ?? 0, 2) }}</h3>
                    <small>Click to refresh</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Pending Requests</h5>
                    <h3 class="mb-0" id="pending-requests-count">{{ $pendingRequests ?? 0 }}</h3>
                    <small>Click to refresh</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('charity.campaigns') }}" class="btn btn-primary w-100">
                                <i class="fa-solid fa-plus me-2"></i>Create Campaign
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('charity.requests') }}" class="btn btn-success w-100">
                                <i class="fa-solid fa-eye me-2"></i>View Requests
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('charity.beneficiaries.index') }}" class="btn btn-warning w-100">
                                <i class="fa-solid fa-users me-2"></i>Manage Beneficiaries
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('charity.reports') }}" class="btn btn-info w-100">
                                <i class="fa-solid fa-chart-bar me-2"></i>Generate Report
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button class="btn btn-secondary w-100" onclick="refreshAll()">
                                <i class="fa-solid fa-sync-alt me-2"></i>Refresh All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="row">
        <!-- Recent Campaigns -->
        <div class="col-md-6 mb-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Campaigns</h5>
                    <button class="btn btn-sm btn-light" onclick="refreshCampaigns()">Refresh</button>
                </div>
                <div class="card-body">
                    <div id="recent-campaigns">
                        @if(isset($recentCampaigns) && $recentCampaigns->count() > 0)
                            @foreach($recentCampaigns as $campaign)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                                    <div>
                                        <strong class="text-dark">{{ $campaign->title }}</strong>
                                        <br>
                                        <small class="text-muted">Goal: ${{ number_format($campaign->goal, 2) }}</small>
                                    </div>
                                    <span class="badge bg-{{ $campaign->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No campaigns found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Donations -->
        <div class="col-md-6 mb-4">
            <div class="card border-info">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Donations</h5>
                    <button class="btn btn-sm btn-light" onclick="refreshDonations()">Refresh</button>
                </div>
                <div class="card-body">
                    <div id="recent-donations">
                        @if(isset($recentDonations) && $recentDonations->count() > 0)
                            @foreach($recentDonations as $donation)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                                    <div>
                                        <strong class="text-dark">${{ number_format($donation->amount, 2) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $donation->campaign->title ?? 'Unknown Campaign' }}</small>
                                    </div>
                                    <small class="text-muted">{{ $donation->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No donations found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="row">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Requests</h5>
                    <button class="btn btn-sm btn-dark" onclick="refreshRequests()">Refresh</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-warning">
                                <tr>
                                    <th>Beneficiary</th>
                                    <th>Request Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="recent-requests-tbody">
                                @if(isset($recentRequests) && $recentRequests->count() > 0)
                                    @foreach($recentRequests as $request)
                                        <tr>
                                            <td class="text-dark">{{ $request->user->name ?? 'Unknown' }}</td>
                                            <td class="text-dark">{{ $request->type ?? 'General' }}</td>
                                            <td class="text-dark">${{ number_format($request->amount ?? 0, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($request->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td class="text-dark">{{ $request->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('charity.requests') }}" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No requests found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast for notifications -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="charity-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="charity-toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
function showCharityToast(message, type = 'success') {
    const toast = document.getElementById('charity-toast');
    const toastBody = document.getElementById('charity-toast-body');
    
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toastBody.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

function refreshAll() {
    refreshStats();
    refreshCampaigns();
    refreshDonations();
    refreshRequests();
    showCharityToast('All data refreshed!');
}

function refreshStats() {
    fetch('{{ route("charity.dashboard.stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('campaigns-count').textContent = data.campaign_stats.total;
                document.getElementById('active-campaigns-count').textContent = data.campaign_stats.active;
                document.getElementById('total-donations').textContent = '$' + parseFloat(data.donation_stats.total_amount).toFixed(2);
                document.getElementById('pending-requests-count').textContent = data.request_stats.pending;
            }
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
        });
}

function refreshCampaigns() {
    // In a real application, you would fetch recent campaigns from an API endpoint
    showCharityToast('Campaigns refreshed!');
}

function refreshDonations() {
    // In a real application, you would fetch recent donations from an API endpoint
    showCharityToast('Donations refreshed!');
}

function refreshRequests() {
    // In a real application, you would fetch recent requests from an API endpoint
    showCharityToast('Requests refreshed!');
}

// Auto-refresh every 30 seconds
setInterval(function() {
    refreshStats();
}, 30000);

// Make stats cards clickable for refresh
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', function() {
        if (this.querySelector('small')) {
            refreshStats();
            showCharityToast('Statistics refreshed!');
        }
    });
});
</script> 