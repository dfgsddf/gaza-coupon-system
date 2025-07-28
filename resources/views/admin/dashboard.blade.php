@extends('layouts.app')

@section('sidebar_title', 'إدارة النظام')

@section('sidebar')
    <div class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
            <i class="fas fa-chart-line me-2"></i> لوحة التحكم
        </a>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active-link' : '' }}">
            <i class="fas fa-users me-2"></i> إدارة المستخدمين
        </a>
        <a href="{{ route('admin.contact-messages.index') }}" class="sidebar-link {{ request()->routeIs('admin.contact-messages.*') ? 'active-link' : '' }}">
            <i class="fas fa-envelope me-2"></i> رسائل التواصل
        </a>
        <a href="/admin/organizations" class="sidebar-link {{ request()->is('admin/organizations*') ? 'active-link' : '' }}">
            <i class="fas fa-building me-2"></i> المنظمات الخيرية
        </a>
        <a href="/admin/stores" class="sidebar-link {{ request()->is('admin/stores*') ? 'active-link' : '' }}">
            <i class="fas fa-store me-2"></i> المتاجر
        </a>
        <a href="/admin/settings" class="sidebar-link {{ request()->is('admin/settings*') ? 'active-link' : '' }}">
            <i class="fas fa-cog me-2"></i> الإعدادات
        </a>
    </div>
    
    <div class="sidebar-footer mt-auto">
        <form action="{{ route('logout') }}" method="POST" class="p-3">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="admin-dashboard">
    <!-- Header -->
    <div class="dashboard-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-1">لوحة تحكم الإدارة</h2>
                <p class="text-muted mb-0">مرحباً بك في لوحة التحكم الرئيسية</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary" id="refresh-all-data">
                    <i class="fas fa-sync-alt me-2"></i> تحديث البيانات
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4" id="admin-stats">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card bg-primary text-white">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-info ms-3">
                        <h3 class="mb-0" id="stat-beneficiaries">{{ $totalBeneficiaries }}</h3>
                        <p class="mb-0">إجمالي المستفيدين</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card bg-success text-white">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="stats-info ms-3">
                        <h3 class="mb-0" id="stat-stores">{{ $activeStores }}</h3>
                        <p class="mb-0">المتاجر النشطة</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card bg-warning text-white">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="stats-info ms-3">
                        <h3 class="mb-0" id="stat-campaigns">{{ $activeCampaigns }}</h3>
                        <p class="mb-0">الحملات النشطة</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card bg-info text-white">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stats-info ms-3">
                        <h3 class="mb-0" id="stat-organizations">{{ $organizations }}</h3>
                        <p class="mb-0">المنظمات الخيرية</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- System Activity -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        نشاط النظام
                    </h5>
                    <button class="btn btn-sm btn-outline-primary" id="refresh-activity">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="activity-list" id="activity-log">
                        <div class="activity-item">
                            <div class="activity-icon bg-success">
                                <i class="fas fa-power-off"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">تم تشغيل النظام</h6>
                                <small class="text-muted">1.6.8.7</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon bg-info">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">تسجيل متجر جديد</h6>
                                <small class="text-muted">2.9.87</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon bg-warning">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">إطلاق حملة جديدة</h6>
                                <small class="text-muted">8.6.5.3</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon bg-primary">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">تحديث الإعدادات</h6>
                                <small class="text-muted">12.8.6.0</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="activity-content">
                                <h6 class="mb-1">تفعيل المتاجر</h6>
                                <small class="text-muted">6.8.9.3</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Management -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2 text-primary"></i>
                        إدارة المستخدمين
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filter-role">
                                <option value="">جميع الأدوار</option>
                                <option value="admin">مدير</option>
                                <option value="charity">جمعية خيرية</option>
                                <option value="store">متجر</option>
                                <option value="beneficiary">مستفيد</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filter-status">
                                <option value="">جميع الحالات</option>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn-sm w-100" id="filter-users">تطبيق الفلتر</button>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>المستخدم</th>
                                    <th>الدور</th>
                                    <th>الحالة</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="users-tbody">
                                @foreach($users as $user)
                                    <tr data-user-id="{{ $user->id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->status == 'active' ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>
                                            <select name="status" class="form-select form-select-sm user-status-select" style="width: auto;">
                                                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>نشط</option>
                                                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">عرض {{ count($users) }} من المستخدمين</small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-right"></i></a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#"><i class="fas fa-angle-left"></i></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="admin-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-info-circle text-primary me-2"></i>
                <strong class="me-auto">إشعار</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="admin-toast-body"></div>
        </div>
    </div>
</div>

<style>
/* Admin Dashboard Specific Styles */
.admin-dashboard {
    padding: 0;
}

.dashboard-header h2 {
    color: #333;
    font-weight: 600;
}

.stats-card {
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: none;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.stats-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
}

.stats-icon i {
    font-size: 1.5rem;
}

.stats-info h3 {
    font-size: 2rem;
    font-weight: 700;
}

.card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 12px 12px 0 0 !important;
    padding: 1rem 1.25rem;
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f1f1;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.activity-content {
    flex: 1;
    margin-right: 0.75rem;
}

.activity-content h6 {
    margin-bottom: 0.25rem;
    color: #333;
    font-weight: 500;
}

.avatar-sm {
    width: 30px;
    height: 30px;
    font-size: 0.8rem;
    font-weight: 600;
}

.table th {
    font-weight: 600;
    color: #555;
    border-bottom: 2px solid #e9ecef;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

.pagination-sm .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .dashboard-header .col-md-4 {
        text-align: center !important;
        margin-top: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .stats-info h3 {
        font-size: 1.5rem;
    }
    
    .activity-content h6 {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .stats-icon {
        width: 40px;
        height: 40px;
    }
    
    .stats-icon i {
        font-size: 1.2rem;
    }
    
    .stats-info h3 {
        font-size: 1.3rem;
    }
    
    .activity-icon {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
}
</style>

<script>
// Toast notification function
function showAdminToast(message, type = 'success') {
    const toast = document.getElementById('admin-toast');
    const toastBody = document.getElementById('admin-toast-body');
    
    // Remove existing classes
    toast.className = 'toast';
    
    // Add appropriate class based on type
    if (type === 'success') {
        toast.classList.add('text-bg-success');
    } else if (type === 'danger') {
        toast.classList.add('text-bg-danger');
    } else {
        toast.classList.add('text-bg-primary');
    }
    
    toastBody.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

// Refresh statistics
document.getElementById('refresh-all-data').addEventListener('click', function() {
    fetch('/admin/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('stat-beneficiaries').textContent = data.totalBeneficiaries;
            document.getElementById('stat-stores').textContent = data.activeStores;
            document.getElementById('stat-campaigns').textContent = data.activeCampaigns;
            document.getElementById('stat-organizations').textContent = data.organizations;
            showAdminToast('تم تحديث الإحصائيات بنجاح');
        })
        .catch(error => {
            showAdminToast('فشل في تحديث الإحصائيات', 'danger');
        });
});

// Refresh activity log
document.getElementById('refresh-activity').addEventListener('click', function() {
    fetch('/admin/dashboard/activity')
        .then(response => response.json())
        .then(data => {
            const activityLog = document.getElementById('activity-log');
            activityLog.innerHTML = '';
            
            data.forEach(function(item) {
                const activityItem = document.createElement('div');
                activityItem.className = 'activity-item';
                activityItem.innerHTML = `
                    <div class="activity-icon bg-success">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="activity-content">
                        <h6 class="mb-1">${item.text}</h6>
                        <small class="text-muted">${item.time}</small>
                    </div>
                `;
                activityLog.appendChild(activityItem);
            });
            
            showAdminToast('تم تحديث سجل النشاط بنجاح');
        })
        .catch(error => {
            showAdminToast('فشل في تحديث سجل النشاط', 'danger');
        });
});

// Filter users
document.getElementById('filter-users').addEventListener('click', function() {
    const role = document.getElementById('filter-role').value;
    const status = document.getElementById('filter-status').value;
    
    const params = new URLSearchParams();
    if (role) params.append('role', role);
    if (status) params.append('status', status);
    
    fetch(`/admin/dashboard/users?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('users-tbody');
            tbody.innerHTML = '';
            
            data.forEach(function(user) {
                const row = document.createElement('tr');
                row.setAttribute('data-user-id', user.id);
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                ${user.name.charAt(0)}
                            </div>
                            <span>${user.name}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</span>
                    </td>
                    <td>
                        <span class="badge ${user.status === 'active' ? 'bg-success' : 'bg-danger'}">
                            ${user.status === 'active' ? 'نشط' : 'غير نشط'}
                        </span>
                    </td>
                    <td>
                        <select name="status" class="form-select form-select-sm user-status-select" style="width: auto;">
                            <option value="active" ${user.status === 'active' ? 'selected' : ''}>نشط</option>
                            <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>غير نشط</option>
                        </select>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            showAdminToast('تم تطبيق الفلتر بنجاح');
        })
        .catch(error => {
            showAdminToast('فشل في تطبيق الفلتر', 'danger');
        });
});

// Handle status change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('user-status-select')) {
        const row = e.target.closest('tr');
        const userId = row.getAttribute('data-user-id');
        const status = e.target.value;
        
        fetch(`/admin/users/${userId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            const statusBadge = row.querySelector('.badge');
            statusBadge.className = `badge ${status === 'active' ? 'bg-success' : 'bg-danger'}`;
            statusBadge.textContent = status === 'active' ? 'نشط' : 'غير نشط';
            showAdminToast('تم تحديث حالة المستخدم بنجاح');
        })
        .catch(error => {
            showAdminToast('فشل في تحديث حالة المستخدم', 'danger');
        });
    }
});

// Auto-refresh data every 5 minutes
setInterval(function() {
    document.getElementById('refresh-all-data').click();
}, 300000);
</script>
@endsection
