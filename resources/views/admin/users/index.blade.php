@extends('layouts.app')

@section('content')
<div class="admin-layout">
    <!-- Main Content Area -->
    <div class="main-content-area full-width">
        <!-- Top Header -->
        {{-- <div class="top-header">
            <div class="header-content">
                <div class="system-title">
                    <div class="system-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    نظام قسائم غزة
                </div>
                <div class="header-nav">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        الرئيسية
                    </a>
                    <a href="/contact">
                        <i class="fas fa-envelope"></i>
                        اتصل بنا
                    </a>
                    <a href="/help">
                        <i class="fas fa-question-circle"></i>
                        المساعدة والدعم
                    </a>
                    <div class="user-info-header">
                        <span>مرحباً مدير النظام</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <div class="dashboard-header">
                <h1 class="dashboard-title">إدارة المستخدمين</h1>
                <p class="dashboard-subtitle">عرض وإدارة جميع مستخدمي النظام</p>
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

            <!-- Statistics Cards -->
            <div class="stats-grid" id="users-stats">
                <div class="stat-card stores">
                    <div class="stat-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="stat-number">{{ $users->where('role', 'store')->count() }}</div>
                    <div class="stat-label">المتاجر</div>
                </div>
                
                <div class="stat-card active-users">
                    <div class="stat-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-number">{{ $users->where('status', 'active')->count() }}</div>
                    <div class="stat-label">المستخدمين النشطين</div>
                </div>
                
                <div class="stat-card total-users">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">{{ $users->count() }}</div>
                    <div class="stat-label">إجمالي المستخدمين</div>
                </div>
            </div>

            <!-- Users Table Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-list text-primary"></i>
                        قائمة المستخدمين
                    </h3>
                    <div class="section-actions">
                        <a href="{{ route('admin.users.create') }}" class="btn-sm btn-primary">
                            <i class="fas fa-plus"></i>
                            إضافة مستخدم جديد
                        </a>
                    </div>
                </div>
                
                <div class="table-filters">
                    <select class="filter-select" id="role-filter">
                        <option value="">جميع الأدوار</option>
                        <option value="admin">مدير</option>
                        <option value="charity">مؤسسة خيرية</option>
                        <option value="store">متجر</option>
                        <option value="beneficiary">مستفيد</option>
                    </select>
                    <select class="filter-select" id="status-filter">
                        <option value="">جميع الحالات</option>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                    <button class="filter-btn" id="apply-filters">تطبيق الفلتر</button>
                </div>
                
                <div class="table-container">
                    <table class="table" id="users-table">
                        <thead>
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
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="user-details">
                                                <div class="user-name">{{ $user->name }}</div>
                                                @if($user->phone)
                                                    <div class="user-phone">{{ $user->phone }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="role-badge
                                            @if($user->role == 'admin') role-admin
                                            @elseif($user->role == 'store') role-store
                                            @elseif($user->role == 'charity') role-charity
                                            @else role-beneficiary @endif">
                                            @if($user->role == 'admin') مدير
                                            @elseif($user->role == 'store') متجر
                                            @elseif($user->role == 'charity') مؤسسة خيرية
                                            @elseif($user->role == 'beneficiary') مستفيد
                                            @else {{ $user->role }} @endif
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.users.updateStatus', $user->id) }}" method="POST" class="status-form">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $user->status == 'active' ? 'inactive' : 'active' }}">
                                            <button type="submit" class="status-btn {{ $user->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                                <i class="fas {{ $user->status == 'active' ? 'fa-check' : 'fa-times' }}"></i>
                                                {{ $user->status == 'active' ? 'نشط' : 'غير نشط' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="registration-date">{{ $user->created_at ? $user->created_at->format('Y-m-d') : 'غير متوفر' }}</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="delete-form">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" title="حذف">
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
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const applyFiltersBtn = document.getElementById('apply-filters');
    applyFiltersBtn.addEventListener('click', function() {
        const roleFilter = document.getElementById('role-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        
        const rows = document.querySelectorAll('#users-table tbody tr');
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
        
        showToast('تم تطبيق الفلتر بنجاح!', 'info');
    });
    
    // Status update functionality
    const statusForms = document.querySelectorAll('.status-form');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('تم تحديث حالة المستخدم بنجاح!', 'success');
                    // Update the button appearance
                    const button = this.querySelector('.status-btn');
                    const icon = button.querySelector('i');
                    const text = button.textContent.trim();
                    
                    if (text.includes('نشط')) {
                        button.className = 'status-btn status-inactive';
                        button.innerHTML = '<i class="fas fa-times"></i> غير نشط';
                    } else {
                        button.className = 'status-btn status-active';
                        button.innerHTML = '<i class="fas fa-check"></i> نشط';
                    }
                } else {
                    showToast('فشل في تحديث حالة المستخدم', 'danger');
                }
            })
            .catch(error => {
                showToast('حدث خطأ أثناء تحديث الحالة', 'danger');
            });
        });
    });
    
    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
                this.submit();
            }
        });
    });
    
    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast show bg-${type} text-white`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
</script>

<style>
/* Users page specific styles */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.user-phone {
    font-size: 0.875rem;
    color: #64748b;
}

.role-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
}

.role-admin {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.role-store {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.role-charity {
    background: linear-gradient(135deg, #10b981, #059669);
}

.role-beneficiary {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
}

.status-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-active {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.status-inactive {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.status-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.registration-date {
    color: #64748b;
    font-size: 0.875rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-edit {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    color: #94a3b8;
    margin-bottom: 1rem;
}

/* Statistics cards specific colors */
.stat-card.stores {
    --card-color: #f59e0b;
    --card-color-light: #fbbf24;
}

.stat-card.active-users {
    --card-color: #10b981;
    --card-color-light: #34d399;
}

.stat-card.total-users {
    --card-color: #3b82f6;
    --card-color-light: #60a5fa;
}

/* Table filters */
.table-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.filter-select {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #1e293b;
    padding: 0.5rem;
    border-radius: 6px;
    font-size: 0.875rem;
    min-width: 150px;
}

.filter-select option {
    background: white;
    color: #1e293b;
}

.filter-btn {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-1px);
}

/* Responsive design */
@media (max-width: 768px) {
    .table-filters {
        flex-direction: column;
    }
    
    .filter-select {
        min-width: auto;
    }
    
    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>
@endsection
