@extends('layouts.app')

@section('sidebar_title', 'إدارة المستخدمين')

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
<div class="admin-users-page">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-1">إدارة المستخدمين</h2>
                <p class="text-muted mb-0">عرض وإدارة جميع مستخدمي النظام</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i> إضافة مستخدم جديد
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Users Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card bg-primary text-white">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-info ms-3">
                        <h3 class="mb-0">{{ $users->count() }}</h3>
                        <p class="mb-0">إجمالي المستخدمين</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card bg-success text-white">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stats-info ms-3">
                        <h3 class="mb-0">{{ $users->where('status', 'active')->count() }}</h3>
                        <p class="mb-0">المستخدمين النشطين</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card bg-warning text-white">
                <div class="d-flex align-items-center">
                    <div class="stats-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="stats-info ms-3">
                        <h3 class="mb-0">{{ $users->where('role', 'store')->count() }}</h3>
                        <p class="mb-0">المتاجر</p>
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
                        <h3 class="mb-0">{{ $users->where('role', 'charity')->count() }}</h3>
                        <p class="mb-0">المنظمات الخيرية</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                قائمة المستخدمين
            </h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" id="role-filter" style="width: auto;">
                    <option value="">جميع الأدوار</option>
                    <option value="admin">مدير</option>
                    <option value="charity">مؤسسة خيرية</option>
                    <option value="store">متجر</option>
                    <option value="beneficiary">مستفيد</option>
                </select>
                <select class="form-select form-select-sm" id="status-filter" style="width: auto;">
                    <option value="">جميع الحالات</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="users-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr data-role="{{ $user->role }}" data-status="{{ $user->status }}">
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            @if($user->phone)
                                                <small class="text-muted">{{ $user->phone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge role-badge
                                        @if($user->role == 'admin') bg-danger
                                        @elseif($user->role == 'store') bg-primary
                                        @elseif($user->role == 'charity') bg-success
                                        @else bg-info @endif">
                                        @if($user->role == 'admin') مدير
                                        @elseif($user->role == 'store') متجر
                                        @elseif($user->role == 'charity') مؤسسة خيرية
                                        @elseif($user->role == 'beneficiary') مستفيد
                                        @else {{ $user->role }} @endif
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.updateStatus', $user->id) }}" method="POST" class="d-inline status-form">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $user->status == 'active' ? 'inactive' : 'active' }}">
                                        <button type="submit" class="btn btn-sm status-btn {{ $user->status == 'active' ? 'btn-success' : 'btn-secondary' }}">
                                            <i class="fas {{ $user->status == 'active' ? 'fa-check' : 'fa-times' }} me-1"></i>
                                            {{ $user->status == 'active' ? 'نشط' : 'غير نشط' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $user->created_at ? $user->created_at->format('Y-m-d') : 'غير متوفر' }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">لا يوجد مستخدمين</h5>
                                        <p class="text-muted">لم يتم العثور على أي مستخدمين في النظام</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="users-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-info-circle text-primary me-2"></i>
                <strong class="me-auto">إشعار</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="users-toast-body"></div>
        </div>
    </div>
</div>

<style>
/* Admin Users Page Specific Styles */
.admin-users-page {
    padding: 0;
}

.page-header h2 {
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

.avatar-md {
    width: 40px;
    height: 40px;
    font-size: 1rem;
    font-weight: 600;
}

.role-badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    font-weight: 500;
}

.status-btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.status-btn:hover {
    transform: translateY(-1px);
}

.btn-group .btn {
    border-radius: 6px !important;
    margin-left: 2px;
}

.empty-state {
    padding: 2rem;
}

/* Table Improvements */
.table th {
    font-weight: 600;
    color: #555;
    border-bottom: 2px solid #e9ecef;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid #f1f1f1;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .page-header .col-md-4 {
        text-align: center !important;
        margin-top: 1rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .card-header .d-flex {
        justify-content: center !important;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .stats-info h3 {
        font-size: 1.5rem;
    }
    
    .avatar-md {
        width: 35px;
        height: 35px;
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
    
    .btn-group {
        flex-direction: column;
        gap: 2px;
    }
    
    .btn-group .btn {
        margin-left: 0;
    }
}
</style>

<script>
// Toast notification function
function showUsersToast(message, type = 'success') {
    const toast = document.getElementById('users-toast');
    const toastBody = document.getElementById('users-toast-body');
    
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

// Status form submission with AJAX
document.querySelectorAll('.status-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const button = this.querySelector('.status-btn');
        const statusInput = this.querySelector('input[name="status"]');
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري التحديث...';
        button.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (statusInput.value === 'active') {
                    button.classList.replace('btn-secondary', 'btn-success');
                    button.innerHTML = '<i class="fas fa-check me-1"></i> نشط';
                    statusInput.value = 'inactive';
                } else {
                    button.classList.replace('btn-success', 'btn-secondary');
                    button.innerHTML = '<i class="fas fa-times me-1"></i> غير نشط';
                    statusInput.value = 'active';
                }
                showUsersToast('تم تحديث حالة المستخدم بنجاح');
            } else {
                button.innerHTML = originalText;
                showUsersToast('فشل في تحديث حالة المستخدم', 'danger');
            }
        })
        .catch(error => {
            button.innerHTML = originalText;
            showUsersToast('حدث خطأ أثناء تحديث حالة المستخدم', 'danger');
        })
        .finally(() => {
            button.disabled = false;
        });
    });
});

// Delete form confirmation
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (confirm('هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.')) {
            this.submit();
        }
    });
});

// Filter functionality
function filterUsers() {
    const roleFilter = document.getElementById('role-filter').value;
    const statusFilter = document.getElementById('status-filter').value;
    const rows = document.querySelectorAll('#users-table tbody tr[data-role]');
    
    rows.forEach(row => {
        const role = row.getAttribute('data-role');
        const status = row.getAttribute('data-status');
        
        const roleMatch = !roleFilter || role === roleFilter;
        const statusMatch = !statusFilter || status === statusFilter;
        
        if (roleMatch && statusMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.getElementById('role-filter').addEventListener('change', filterUsers);
document.getElementById('status-filter').addEventListener('change', filterUsers);
</script>
@endsection
