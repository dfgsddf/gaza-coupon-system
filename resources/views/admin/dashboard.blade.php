@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 d-none d-lg-block sidebar bg-primary text-white p-4" style="min-height: 100vh;">
            <h5 class="px-3 mb-4">Gaza<br />Faders</h5>
            <a href="#" class="d-block text-white mb-3"><i class="fa fa-home me-2"></i> Admin</a>
            <a href="/admin/users" class="d-block text-white mb-3 {{ request()->is('admin/users*') ? 'active' : '' }}"><i class="fa fa-users me-2"></i> User Management</a>
            <a href="/admin/organizations" class="d-block text-white mb-3 {{ request()->is('admin/organizations*') ? 'active' : '' }}"><i class="fa fa-building me-2"></i> Organization</a>
            <a href="/admin/stores" class="d-block text-white mb-3 {{ request()->is('admin/stores*') ? 'active' : '' }}"><i class="fa fa-store me-2"></i> Stores</a>
            <a href="/admin/settings" class="d-block text-white mb-3 {{ request()->is('admin/settings*') ? 'active' : '' }}"><i class="fa fa-gear me-2"></i> Settings</a>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="btn btn-link text-white p-0"><i class="fa fa-sign-out-alt me-2"></i> Logout</button>
            </form>
        </div>
        <!-- Main Content -->
        <div class="col-lg-10 col-12 p-4">
            <h3 class="mb-4 text-white">Admin Dashboard <button class="btn btn-sm btn-outline-light ms-2" id="refresh-stats">Refresh</button></h3>
            <!-- Stats -->
            <div class="row g-3 mb-4" id="admin-stats">
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded bg-secondary text-white text-center">
                        <p>Total Beneficiaries</p>
                        <h3 id="stat-beneficiaries">{{ $totalBeneficiaries }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded bg-secondary text-white text-center">
                        <p>Active Stores</p>
                        <h3 id="stat-stores">{{ $activeStores }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded bg-secondary text-white text-center">
                        <p>Active Campaigns</p>
                        <h3 id="stat-campaigns">{{ $activeCampaigns }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="p-3 rounded bg-secondary text-white text-center">
                        <p>Organizations</p>
                        <h3 id="stat-organizations">{{ $organizations }}</h3>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <!-- System Activity -->
                <div class="col-md-6">
                    <div class="card bg-dark text-white p-3">
                        <h5>System Activity <button class="btn btn-sm btn-outline-light ms-2" id="refresh-activity">Refresh</button></h5>
                        <ul class="list-unstyled mt-3" id="activity-log">
                            <li class="mb-2">System Started <small class="text-muted ms-2">1.6.8.7</small></li>
                            <li class="mb-2">New Store Registered <small class="text-muted ms-2">2.9.87</small></li>
                            <li class="mb-2">Campaign Launched <small class="text-muted ms-2">8.6.5.3</small></li>
                            <li class="mb-2">Settings Updated <small class="text-muted ms-2">12.8.6.0</small></li>
                            <li class="mb-2">Activate Stores <small class="text-muted ms-2">6.8.9.3</small></li>
                        </ul>
                    </div>
                </div>
                <!-- User Management -->
                <div class="col-md-6">
                    <div class="card bg-dark text-white p-3">
                        <h5>User Management</h5>
                        <div class="d-flex flex-wrap mb-3">
                            <select class="form-select me-2 mb-2" id="filter-role">
                                <option value="">Role</option>
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="editor">Editor</option>
                                <option value="support">Support</option>
                            </select>
                            <select class="form-select me-2 mb-2" id="filter-status">
                                <option value="">Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <button class="btn btn-primary mb-2" id="filter-users">Filter</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-dark" id="users-table">
                                <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Change Status</th>
                                </tr>
                                </thead>
                                <tbody id="users-tbody">
                                @foreach($users as $user)
                                    <tr data-user-id="{{ $user->id }}">
                                        <td>{{ $user->name }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>{{ ucfirst($user->status) }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.users.updateStatus', $user->id) }}" class="d-inline user-status-form">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm d-inline w-auto user-status-select">
                                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Info Page</span>
                            <span id="user-pagination">Page 1 <i class="fa fa-angle-left mx-2"></i><i class="fa fa-angle-right"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Toasts -->
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                <div id="admin-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="admin-toast-body"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>
        </div> <!-- End Main Content -->
    </div>
</div>
<script>
function showAdminToast(msg, type = 'success') {
    const toast = $('#admin-toast');
    toast.removeClass('text-bg-success text-bg-danger text-bg-info').addClass('text-bg-' + type);
    $('#admin-toast-body').text(msg);
    toast.toast('show');
}
$('#refresh-stats').on('click', function() {
    $.get('/admin/dashboard/stats', function(data) {
        $('#stat-beneficiaries').text(data.totalBeneficiaries);
        $('#stat-stores').text(data.activeStores);
        $('#stat-campaigns').text(data.activeCampaigns);
        $('#stat-organizations').text(data.organizations);
        showAdminToast('Stats refreshed!');
    });
});
$('#refresh-activity').on('click', function() {
    $.get('/admin/dashboard/activity', function(data) {
        let html = '';
        data.forEach(function(item) {
            html += `<li class="mb-2">${item.text} <small class="text-muted ms-2">${item.time}</small></li>`;
        });
        $('#activity-log').html(html);
        showAdminToast('Activity log refreshed!');
    });
});
$('#filter-users').on('click', function() {
    const role = $('#filter-role').val();
    const status = $('#filter-status').val();
    $.get('/admin/dashboard/users', { role, status }, function(data) {
        let html = '';
        data.forEach(function(user) {
            html += `<tr data-user-id="${user.id}">
                <td>${user.name}</td>
                <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                <td>${user.status.charAt(0).toUpperCase() + user.status.slice(1)}</td>
                <td>
                    <form class="d-inline user-status-form">
                        <select name="status" class="form-select form-select-sm d-inline w-auto user-status-select">
                            <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                            <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                        </select>
                    </form>
                </td>
            </tr>`;
        });
        $('#users-tbody').html(html);
        showAdminToast('Users filtered!');
    });
});
$(document).on('change', '.user-status-select', function() {
    const row = $(this).closest('tr');
    const userId = row.data('user-id');
    const status = $(this).val();
    $.ajax({
        url: `/admin/users/${userId}/status`,
        method: 'PATCH',
        data: { status, _token: '{{ csrf_token() }}' },
        success: function(res) {
            showAdminToast('Status updated!', 'success');
            row.find('td').eq(2).text(status.charAt(0).toUpperCase() + status.slice(1));
        },
        error: function() {
            showAdminToast('Failed to update status.', 'danger');
        }
    });
});
// Auto-refresh stats and activity every 30s
setInterval(function() {
    $('#refresh-stats').click();
    $('#refresh-activity').click();
}, 30000);
</script>
@endsection
