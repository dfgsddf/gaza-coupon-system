@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 d-none d-lg-block sidebar bg-primary text-white p-4" style="min-height: 100vh;">
            <h5 class="px-3 mb-4">Gaza<br />Faders</h5>
            <a href="{{ route('admin.dashboard') }}" class="d-block text-white mb-3"><i class="fa fa-home me-2"></i> Admin</a>
            <a href="/admin/users" class="d-block text-white mb-3"><i class="fa fa-users me-2"></i> User Management</a>
            <a href="/admin/organizations" class="d-block text-white mb-3"><i class="fa fa-building me-2"></i> Organization</a>
            <a href="/admin/stores" class="d-block text-white mb-3"><i class="fa fa-store me-2"></i> Stores</a>
            <a href="/admin/settings" class="d-block text-white mb-3 active"><i class="fa fa-gear me-2"></i> Settings</a>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button class="btn btn-link text-white p-0"><i class="fa fa-sign-out-alt me-2"></i> Logout</button>
            </form>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-10 col-12 p-4">
            <h3 class="mb-4 text-white">Admin Settings</h3>
            
            <div class="row">
                <!-- Profile Settings -->
                <div class="col-md-6 mb-4">
                    <div class="card bg-dark text-white">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-user me-2"></i>Profile Settings</h5>
                        </div>
                        <div class="card-body">
                            <form id="profile-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Password Change -->
                <div class="col-md-6 mb-4">
                    <div class="card bg-dark text-white">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-lock me-2"></i>Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form id="password-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-warning">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="col-md-6 mb-4">
                    <div class="card bg-dark text-white">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-cog me-2"></i>System Settings</h5>
                        </div>
                        <div class="card-body">
                            <form id="system-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" class="form-control" id="site_name" name="site_name" value="Gaza Coupon System">
                                </div>
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Admin Email</label>
                                    <input type="email" class="form-control" id="admin_email" name="admin_email" value="{{ Auth::user()->email }}">
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                                        <label class="form-check-label" for="maintenance_mode">
                                            Maintenance Mode
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Save Settings</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="col-md-6 mb-4">
                    <div class="card bg-dark text-white">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fa fa-bell me-2"></i>Notification Settings</h5>
                        </div>
                        <div class="card-body">
                            <form id="notification-form">
                                @csrf
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="new_user_notifications" name="new_user_notifications" checked>
                                        <label class="form-check-label" for="new_user_notifications">
                                            New User Registrations
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="campaign_notifications" name="campaign_notifications" checked>
                                        <label class="form-check-label" for="campaign_notifications">
                                            New Campaign Launches
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="transaction_notifications" name="transaction_notifications" checked>
                                        <label class="form-check-label" for="transaction_notifications">
                                            High-Value Transactions
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="system_alerts" name="system_alerts" checked>
                                        <label class="form-check-label" for="system_alerts">
                                            System Alerts
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info">Save Notifications</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast for notifications -->
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                <div id="settings-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body" id="settings-toast-body"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showSettingsToast(message, type = 'success') {
    const toast = document.getElementById('settings-toast');
    const toastBody = document.getElementById('settings-toast-body');
    
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toastBody.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

// Profile form submission
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/settings/profile', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSettingsToast('Profile updated successfully!', 'success');
        } else {
            showSettingsToast(data.message || 'Failed to update profile.', 'danger');
        }
    })
    .catch(error => {
        showSettingsToast('An error occurred. Please try again.', 'danger');
    });
});

// Password form submission
document.getElementById('password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/settings/password', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSettingsToast('Password changed successfully!', 'success');
            this.reset();
        } else {
            showSettingsToast(data.message || 'Failed to change password.', 'danger');
        }
    })
    .catch(error => {
        showSettingsToast('An error occurred. Please try again.', 'danger');
    });
});

// System settings form submission
document.getElementById('system-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/settings/system', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSettingsToast('System settings saved successfully!', 'success');
        } else {
            showSettingsToast(data.message || 'Failed to save settings.', 'danger');
        }
    })
    .catch(error => {
        showSettingsToast('An error occurred. Please try again.', 'danger');
    });
});

// Notification settings form submission
document.getElementById('notification-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/settings/notifications', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSettingsToast('Notification settings saved successfully!', 'success');
        } else {
            showSettingsToast(data.message || 'Failed to save notifications.', 'danger');
        }
    })
    .catch(error => {
        showSettingsToast('An error occurred. Please try again.', 'danger');
    });
});
</script>
@endsection 