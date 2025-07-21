@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 d-none d-lg-block sidebar bg-primary text-white p-4">
                <h5 class="mb-4"><i class="fa-solid fa-user"></i> Beneficiary Dashboard</h5>
                <a href="{{ route('beneficiary.dashboard') }}" class="d-block text-white mb-3 {{ request()->routeIs('beneficiary.dashboard') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="{{ route('requests.index') }}" class="d-block text-white mb-3 {{ request()->routeIs('requests.*') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-code-pull-request"></i> Requests
                </a>
                <a href="{{ route('coupons.index') }}" class="d-block text-white mb-3 {{ request()->routeIs('coupons.*') ? 'active-link' : '' }}">
                    <i class="fa-solid fa-ticket"></i> Available Coupon
                </a>
                <a href="{{ route('beneficiary.settings') }}" class="d-block text-white mb-3 {{ request()->routeIs('beneficiary.settings') ? 'active-link' : '' }}">
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
                        <h4>Settings</h4>
                        <p class="text-muted mb-0">Manage your account and preferences</p>
                    </div>
                    <a href="{{ route('beneficiary.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Settings Tabs -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                                            <i class="fa-solid fa-user"></i> Profile
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                            <i class="fa-solid fa-shield"></i> Security
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                                            <i class="fa-solid fa-bell"></i> Notifications
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="settingsTabsContent">
                                    <!-- Profile Tab -->
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                        <form id="profile-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="name" class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                                    <div class="invalid-feedback" id="name-error"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="email" class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                                    <div class="invalid-feedback" id="email-error"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="phone" class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? '' }}" placeholder="+970XXXXXXXXX">
                                                    <div class="invalid-feedback" id="phone-error"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address">{{ $user->address ?? '' }}</textarea>
                                                    <div class="invalid-feedback" id="address-error"></div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary" id="profile-submit-btn">
                                                <i class="fa-solid fa-save"></i> 
                                                <span class="btn-text">Save Changes</span>
                                                <span class="btn-loading" style="display: none;">
                                                    <i class="fa-solid fa-spinner fa-spin"></i> Saving...
                                                </span>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Security Tab -->
                                    <div class="tab-pane fade" id="security" role="tabpanel">
                                        <form id="password-form">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="current_password" class="form-label">Current Password</label>
                                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                                    <div class="invalid-feedback" id="current_password-error"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="new_password" class="form-label">New Password</label>
                                                    <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                                                    <div class="form-text">Minimum 6 characters</div>
                                                    <div class="invalid-feedback" id="new_password-error"></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                                    <div class="invalid-feedback" id="new_password_confirmation-error"></div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-warning" id="password-submit-btn">
                                                <i class="fa-solid fa-key"></i> 
                                                <span class="btn-text">Change Password</span>
                                                <span class="btn-loading" style="display: none;">
                                                    <i class="fa-solid fa-spinner fa-spin"></i> Changing...
                                                </span>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Notifications Tab -->
                                    <div class="tab-pane fade" id="notifications" role="tabpanel">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6>Email Notifications</h6>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="email_requests" checked>
                                                    <label class="form-check-label" for="email_requests">
                                                        Request status updates
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="email_coupons" checked>
                                                    <label class="form-check-label" for="email_coupons">
                                                        New coupon notifications
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="email_news" checked>
                                                    <label class="form-check-label" for="email_news">
                                                        News and updates
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6>Dashboard Notifications</h6>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="dashboard_alerts" checked>
                                                    <label class="form-check-label" for="dashboard_alerts">
                                                        Show alerts and notifications
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="auto_refresh" checked>
                                                    <label class="form-check-label" for="auto_refresh">
                                                        Auto-refresh dashboard data
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success mt-3" id="notifications-submit-btn" onclick="saveNotificationSettings()">
                                            <i class="fa-solid fa-save"></i> 
                                            <span class="btn-text">Save Preferences</span>
                                            <span class="btn-loading" style="display: none;">
                                                <i class="fa-solid fa-spinner fa-spin"></i> Saving...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Statistics -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Requests</h5>
                                <h2 class="text-primary">{{ $user->requests()->count() }}</h2>
                                <p class="card-text">Requests submitted</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Active Coupons</h5>
                                <h2 class="text-success">{{ $user->coupons()->count() }}</h2>
                                <p class="card-text">Available coupons</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Member Since</h5>
                                <h2 class="text-info">{{ $user->created_at->format('M Y') }}</h2>
                                <p class="card-text">{{ $user->created_at->diffForHumans() }}</p>
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
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link.active {
            color: #007bff;
            border-bottom: 2px solid #007bff;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
        }
    </style>

    <script>
        // Profile form submission
        $('#profile-form').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#profile-submit-btn');
            const btnText = btn.find('.btn-text');
            const btnLoading = btn.find('.btn-loading');
            
            // Clear previous errors
            clearFormErrors();
            
            // Show loading state
            btn.prop('disabled', true);
            btnText.hide();
            btnLoading.show();
            
            $.ajax({
                url: '{{ route("beneficiary.settings.profile") }}',
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        // Update form values with fresh data
                        if (response.user) {
                            $('#name').val(response.user.name);
                            $('#email').val(response.user.email);
                            $('#phone').val(response.user.phone || '');
                            $('#address').val(response.user.address || '');
                        }
                    } else {
                        showNotification(response.message || 'Failed to update profile', 'error');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        displayValidationErrors(errors);
                        showNotification('Please fix the validation errors', 'error');
                    } else {
                        showNotification('Failed to update profile. Please try again.', 'error');
                    }
                },
                complete: function() {
                    // Hide loading state
                    btn.prop('disabled', false);
                    btnText.show();
                    btnLoading.hide();
                }
            });
        });

        // Password form submission
        $('#password-form').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#password-submit-btn');
            const btnText = btn.find('.btn-text');
            const btnLoading = btn.find('.btn-loading');
            
            // Clear previous errors
            clearFormErrors();
            
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#new_password_confirmation').val();
            
            if (newPassword !== confirmPassword) {
                $('#new_password_confirmation').addClass('is-invalid');
                $('#new_password_confirmation-error').text('Passwords do not match');
                return;
            }
            
            // Show loading state
            btn.prop('disabled', true);
            btnText.hide();
            btnLoading.show();
            
            $.ajax({
                url: '{{ route("beneficiary.settings.password") }}',
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        $('#password-form')[0].reset();
                    } else {
                        showNotification(response.message || 'Failed to change password', 'error');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        displayValidationErrors(errors);
                        showNotification('Please fix the validation errors', 'error');
                    } else {
                        showNotification('Failed to change password. Please try again.', 'error');
                    }
                },
                complete: function() {
                    // Hide loading state
                    btn.prop('disabled', false);
                    btnText.show();
                    btnLoading.hide();
                }
            });
        });

        // Save notification settings
        function saveNotificationSettings() {
            const btn = $('#notifications-submit-btn');
            const btnText = btn.find('.btn-text');
            const btnLoading = btn.find('.btn-loading');
            
            // Show loading state
            btn.prop('disabled', true);
            btnText.hide();
            btnLoading.show();
            
            const settings = {
                email_requests: $('#email_requests').is(':checked'),
                email_coupons: $('#email_coupons').is(':checked'),
                email_news: $('#email_news').is(':checked'),
                dashboard_alerts: $('#dashboard_alerts').is(':checked'),
                auto_refresh: $('#auto_refresh').is(':checked'),
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            
            $.ajax({
                url: '{{ route("beneficiary.settings.notifications") }}',
                method: 'POST',
                data: settings,
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        // Store in localStorage for demo purposes
                        localStorage.setItem('beneficiary_notifications', JSON.stringify(response.settings));
                    } else {
                        showNotification(response.message || 'Failed to save notification settings', 'error');
                    }
                },
                error: function() {
                    showNotification('Failed to save notification settings. Please try again.', 'error');
                },
                complete: function() {
                    // Hide loading state
                    btn.prop('disabled', false);
                    btnText.show();
                    btnLoading.hide();
                }
            });
        }

        // Display validation errors
        function displayValidationErrors(errors) {
            Object.keys(errors).forEach(function(field) {
                const input = $('#' + field);
                const errorDiv = $('#' + field + '-error');
                
                if (input.length && errorDiv.length) {
                    input.addClass('is-invalid');
                    errorDiv.text(errors[field][0]);
                }
            });
        }

        // Clear form errors
        function clearFormErrors() {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        // Confirm logout
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                $('#logout-form').submit();
            }
        }

        // Show notification
        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            const notification = $(`
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="fa-solid ${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            $('body').append(notification);
            
            setTimeout(function() {
                notification.alert('close');
            }, 5000);
        }

        // Phone number formatting
        $('#phone').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 0) {
                if (!value.startsWith('970')) {
                    value = '970' + value;
                }
                value = '+' + value;
            }
            $(this).val(value);
        });

        // Load saved notification settings
        $(document).ready(function() {
            const savedSettings = localStorage.getItem('beneficiary_notifications');
            if (savedSettings) {
                const settings = JSON.parse(savedSettings);
                $('#email_requests').prop('checked', settings.email_requests);
                $('#email_coupons').prop('checked', settings.email_coupons);
                $('#email_news').prop('checked', settings.email_news);
                $('#dashboard_alerts').prop('checked', settings.dashboard_alerts);
                $('#auto_refresh').prop('checked', settings.auto_refresh);
            }
        });
    </script>
@endsection 