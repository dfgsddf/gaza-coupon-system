@extends('layouts.app')

@section('sidebar')
    <div class="p-4">
        <h5 class="mb-4">Gaza Coupon</h5>
        <a href="{{ route('charity.dashboard') }}" class="d-block text-white mb-3"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="{{ route('charity.campaigns') }}" class="d-block text-white mb-3"><i class="fa-solid fa-compass"></i> Campaigns</a>
        <a href="{{ route('charity.requests') }}" class="d-block text-white mb-3"><i class="fa-solid fa-code-pull-request"></i> Requests</a>
        <a href="{{ route('charity.reports') }}" class="d-block text-white mb-3"><i class="fa-solid fa-book-open"></i> Reports</a>
        <a href="{{ route('charity.settings') }}" class="d-block text-white mb-3 active"><i class="fa-solid fa-gear"></i> Settings</a>
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
        <h2 class="text-white mb-4">Charity Settings</h2>

        <!-- Settings Navigation -->
        <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                    <i class="fa fa-user me-2"></i>Profile
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                    <i class="fa fa-shield me-2"></i>Security
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                    <i class="fa fa-bell me-2"></i>Notifications
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">
                    <i class="fa fa-cog me-2"></i>Preferences
                </button>
            </li>
        </ul>

        <!-- Settings Content -->
        <div class="tab-content" id="settingsTabContent">
            <!-- Profile Settings -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5 class="mb-0">Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form id="profile-form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="charity-name" class="form-label">Charity Name</label>
                                    <input type="text" class="form-control" id="charity-name" value="Gaza Relief Foundation">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact-email" class="form-label">Contact Email</label>
                                    <input type="email" class="form-control" id="contact-email" value="contact@gazarelief.org">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" value="+970 59 123 4567">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" class="form-control" id="website" value="https://gazarelief.org">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" rows="3">123 Gaza Street, Gaza City, Palestine</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" rows="4">We are dedicated to providing humanitarian aid and support to the people of Gaza through various programs and initiatives.</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Save Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5 class="mb-0">Security Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="password-form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current-password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current-password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="new-password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new-password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="confirm-password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm-password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password Strength</label>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" id="password-strength" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted" id="password-feedback">Enter a password to check strength</small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-key me-2"></i>Change Password
                            </button>
                        </form>

                        <hr class="my-4">

                        <h6>Two-Factor Authentication</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable-2fa">
                            <label class="form-check-label" for="enable-2fa">
                                Enable Two-Factor Authentication
                            </label>
                        </div>
                        <small class="text-muted">Add an extra layer of security to your account</small>

                        <hr class="my-4">

                        <h6>Login Sessions</h6>
                        <div class="table-responsive">
                            <table class="table table-dark table-sm">
                                <thead>
                                    <tr>
                                        <th>Device</th>
                                        <th>Location</th>
                                        <th>Last Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Chrome on Windows</td>
                                        <td>Gaza, Palestine</td>
                                        <td>Now</td>
                                        <td><span class="badge bg-success">Current</span></td>
                                    </tr>
                                    <tr>
                                        <td>Firefox on Android</td>
                                        <td>Gaza, Palestine</td>
                                        <td>2 hours ago</td>
                                        <td><button class="btn btn-sm btn-outline-danger">Revoke</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="tab-pane fade" id="notifications" role="tabpanel">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5 class="mb-0">Notification Preferences</h5>
                    </div>
                    <div class="card-body">
                        <h6>Email Notifications</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="email-requests" checked>
                            <label class="form-check-label" for="email-requests">
                                New assistance requests
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="email-donations" checked>
                            <label class="form-check-label" for="email-donations">
                                New donations received
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="email-reports">
                            <label class="form-check-label" for="email-reports">
                                Weekly/monthly reports
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="email-updates">
                            <label class="form-check-label" for="email-updates">
                                System updates and maintenance
                            </label>
                        </div>

                        <h6>Push Notifications</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="push-urgent" checked>
                            <label class="form-check-label" for="push-urgent">
                                Urgent requests
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="push-reminders">
                            <label class="form-check-label" for="push-reminders">
                                Reminders for pending requests
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="push-achievements">
                            <label class="form-check-label" for="push-achievements">
                                Campaign milestones and achievements
                            </label>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="saveNotificationSettings()">
                            <i class="fa fa-save me-2"></i>Save Notification Settings
                        </button>
                    </div>
                </div>
            </div>

            <!-- Preferences Settings -->
            <div class="tab-pane fade" id="preferences" role="tabpanel">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5 class="mb-0">System Preferences</h5>
                    </div>
                    <div class="card-body">
                        <h6>Display Settings</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="language" class="form-label">Language</label>
                                <select class="form-select" id="language">
                                    <option value="en">English</option>
                                    <option value="ar" selected>العربية</option>
                                    <option value="fr">Français</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select class="form-select" id="timezone">
                                    <option value="Asia/Gaza" selected>Asia/Gaza (GMT+2)</option>
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">America/New_York</option>
                                </select>
                            </div>
                        </div>

                        <h6>Data Management</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="auto-backup" class="form-label">Auto Backup Frequency</label>
                                <select class="form-select" id="auto-backup">
                                    <option value="daily">Daily</option>
                                    <option value="weekly" selected>Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="never">Never</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data-retention" class="form-label">Data Retention Period</label>
                                <select class="form-select" id="data-retention">
                                    <option value="1">1 year</option>
                                    <option value="3" selected>3 years</option>
                                    <option value="5">5 years</option>
                                    <option value="forever">Forever</option>
                                </select>
                            </div>
                        </div>

                        <h6>Privacy Settings</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="analytics" checked>
                            <label class="form-check-label" for="analytics">
                                Allow analytics and usage data collection
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="public-profile">
                            <label class="form-check-label" for="public-profile">
                                Make charity profile public
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="contact-info">
                            <label class="form-check-label" for="contact-info">
                                Show contact information to beneficiaries
                            </label>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="savePreferences()">
                            <i class="fa fa-save me-2"></i>Save Preferences
                        </button>
                    </div>
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
    showSettingsToast('Profile updated successfully!');
});

// Password form submission
document.getElementById('password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    if (newPassword !== confirmPassword) {
        showSettingsToast('Passwords do not match!', 'danger');
        return;
    }
    
    if (newPassword.length < 8) {
        showSettingsToast('Password must be at least 8 characters long!', 'danger');
        return;
    }
    
    showSettingsToast('Password changed successfully!');
    document.getElementById('password-form').reset();
});

// Password strength checker
document.getElementById('new-password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('password-strength');
    const feedback = document.getElementById('password-feedback');
    
    let strength = 0;
    let feedbackText = '';
    
    if (password.length >= 8) strength += 25;
    if (/[a-z]/.test(password)) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    
    strengthBar.style.width = strength + '%';
    
    if (strength <= 25) {
        strengthBar.className = 'progress-bar bg-danger';
        feedbackText = 'Weak password';
    } else if (strength <= 50) {
        strengthBar.className = 'progress-bar bg-warning';
        feedbackText = 'Fair password';
    } else if (strength <= 75) {
        strengthBar.className = 'progress-bar bg-info';
        feedbackText = 'Good password';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        feedbackText = 'Strong password';
    }
    
    feedback.textContent = feedbackText;
});

function saveNotificationSettings() {
    showSettingsToast('Notification settings saved successfully!');
}

function savePreferences() {
    showSettingsToast('Preferences saved successfully!');
}

// Two-factor authentication toggle
document.getElementById('enable-2fa').addEventListener('change', function() {
    if (this.checked) {
        showSettingsToast('Two-factor authentication enabled!', 'info');
    } else {
        showSettingsToast('Two-factor authentication disabled!', 'warning');
    }
});
</script>
@endsection 