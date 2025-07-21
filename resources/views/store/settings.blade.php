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
                        <h4>Store Settings</h4>
                        <p class="text-muted mb-0">Manage your store profile and preferences</p>
                    </div>
                    <a href="{{ route('store.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Settings Tabs -->
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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="store-tab" data-bs-toggle="tab" data-bs-target="#store" type="button" role="tab">
                                    <i class="fa-solid fa-store"></i> Store Info
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="settingsTabContent">
                            <!-- Profile Tab -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                <form id="profileForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="storeName" class="form-label">Store Name</label>
                                                <input type="text" class="form-control" id="storeName" value="{{ $user->name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="storeEmail" class="form-label">Email Address</label>
                                                <input type="email" class="form-control" id="storeEmail" value="{{ $user->email }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="storePhone" class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control" id="storePhone" value="+970 59 123 4567">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="storeAddress" class="form-label">Store Address</label>
                                                <input type="text" class="form-control" id="storeAddress" value="Gaza City, Palestine">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="storeDescription" class="form-label">Store Description</label>
                                        <textarea class="form-control" id="storeDescription" rows="3" placeholder="Describe your store...">A trusted store providing quality products and services to the community.</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-save"></i> Save Profile
                                    </button>
                                </form>
                            </div>

                            <!-- Security Tab -->
                            <div class="tab-pane fade" id="security" role="tabpanel">
                                <form id="securityForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="currentPassword" class="form-label">Current Password</label>
                                                <input type="password" class="form-control" id="currentPassword" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="newPassword" class="form-label">New Password</label>
                                                <input type="password" class="form-control" id="newPassword" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                                <input type="password" class="form-control" id="confirmPassword" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="enable2FA">
                                                    <label class="form-check-label" for="enable2FA">
                                                        Enable Two-Factor Authentication
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-shield"></i> Update Security
                                    </button>
                                </form>
                            </div>

                            <!-- Notifications Tab -->
                            <div class="tab-pane fade" id="notifications" role="tabpanel">
                                <div class="mb-4">
                                    <h6>Email Notifications</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="emailTransactions" checked>
                                        <label class="form-check-label" for="emailTransactions">
                                            New transaction notifications
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="emailReports" checked>
                                        <label class="form-check-label" for="emailReports">
                                            Daily/weekly reports
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="emailAlerts">
                                        <label class="form-check-label" for="emailAlerts">
                                            System alerts and updates
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <h6>In-App Notifications</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="appTransactions" checked>
                                        <label class="form-check-label" for="appTransactions">
                                            New transaction alerts
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="appUpdates" checked>
                                        <label class="form-check-label" for="appUpdates">
                                            System updates
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="appReminders">
                                        <label class="form-check-label" for="appReminders">
                                            Daily reminders
                                        </label>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="saveNotifications()">
                                    <i class="fa-solid fa-bell"></i> Save Notifications
                                </button>
                            </div>

                            <!-- Store Info Tab -->
                            <div class="tab-pane fade" id="store" role="tabpanel">
                                <form id="storeInfoForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="storeCategory" class="form-label">Store Category</label>
                                                <select class="form-select" id="storeCategory">
                                                    <option value="grocery">Grocery Store</option>
                                                    <option value="pharmacy">Pharmacy</option>
                                                    <option value="clothing">Clothing Store</option>
                                                    <option value="electronics">Electronics</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="storeHours" class="form-label">Operating Hours</label>
                                                <input type="text" class="form-control" id="storeHours" value="8:00 AM - 10:00 PM">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="storeWebsite" class="form-label">Website (Optional)</label>
                                                <input type="url" class="form-control" id="storeWebsite" placeholder="https://yourstore.com">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="storeSocial" class="form-label">Social Media (Optional)</label>
                                                <input type="text" class="form-control" id="storeSocial" placeholder="@yourstore">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="storeServices" class="form-label">Services Offered</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="serviceDelivery" checked>
                                                    <label class="form-check-label" for="serviceDelivery">Home Delivery</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="servicePickup" checked>
                                                    <label class="form-check-label" for="servicePickup">Pickup Service</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="serviceSupport">
                                                    <label class="form-check-label" for="serviceSupport">Customer Support</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-store"></i> Save Store Info
                                    </button>
                                </form>
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
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
        }
    </style>

    <script>
        // CSRF Token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Profile form submission
        $('#profileForm').on('submit', function(e) {
            e.preventDefault();
            showAlert('Profile updated successfully!', 'success');
        });

        // Security form submission
        $('#securityForm').on('submit', function(e) {
            e.preventDefault();
            const newPassword = $('#newPassword').val();
            const confirmPassword = $('#confirmPassword').val();
            
            if (newPassword !== confirmPassword) {
                showAlert('New passwords do not match!', 'danger');
                return;
            }
            
            showAlert('Security settings updated successfully!', 'success');
        });

        // Store info form submission
        $('#storeInfoForm').on('submit', function(e) {
            e.preventDefault();
            showAlert('Store information updated successfully!', 'success');
        });

        // Save notifications
        function saveNotifications() {
            showAlert('Notification preferences saved successfully!', 'success');
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
    </script>
@endsection 