@extends('layouts.app')

@section('sidebar_title', 'لوحة التحكم')
@section('sidebar')
    <div class="sidebar-section">
        <h6 class="sidebar-section-title">إدارة النظام</h6>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    لوحة التحكم
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    إدارة المستخدمين
                </a>
            </li>
            <li>
                <a href="{{ route('admin.contact-messages.index') }}" class="sidebar-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    رسائل التواصل
                </a>
            </li>
            <li>
                <a href="/admin/organizations" class="sidebar-link {{ request()->is('admin/organizations*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    المنظمات الخيرية
                </a>
            </li>
            <li>
                <a href="/admin/stores" class="sidebar-link {{ request()->is('admin/stores*') ? 'active' : '' }}">
                    <i class="fas fa-store"></i>
                    المتاجر
                </a>
            </li>
            <li>
                <a href="/admin/settings" class="sidebar-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    الإعدادات
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-section">
        <h6 class="sidebar-section-title">إجراءات سريعة</h6>
        <ul class="sidebar-nav">
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-plus"></i>
                    إضافة مستخدم جديد
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-file-alt"></i>
                    تقرير النظام
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="fas fa-download"></i>
                    تصدير البيانات
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('sidebar_footer')
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link w-100 text-start">
                <i class="fas fa-sign-out-alt"></i>
                تسجيل الخروج
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title">لوحة التحكم الرئيسية</h1>
                <p class="page-subtitle">مرحباً بك في لوحة تحكم الإدارة</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" onclick="refreshData()">
                    <i class="fas fa-sync-alt me-2"></i>
                    تحديث البيانات
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $totalOrganizations ?? 4 }}</div>
                            <div class="stats-label">المنظمات الخيرية</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $activeCampaigns ?? 24 }}</div>
                            <div class="stats-label">الحملات النشطة</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $activeStores ?? 2 }}</div>
                            <div class="stats-label">المتاجر النشطة</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-store"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number">{{ $totalBeneficiaries ?? 210 }}</div>
                            <div class="stats-label">إجمالي المستفيدين</div>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="row">
        <!-- User Management Section -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        إدارة المستخدمين
                    </h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm">جميع الأدوار</button>
                        <button class="btn btn-outline-primary btn-sm">جميع الحالات</button>
                        <button class="btn btn-primary btn-sm">تطبيق الفلتر</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>المستخدم</th>
                                    <th>الدور</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>أحمد محمد</td>
                                    <td><span class="badge bg-primary">مدير</span></td>
                                    <td><span class="badge bg-success">نشط</span></td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm">تعديل</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>فاطمة علي</td>
                                    <td><span class="badge bg-info">مشرف</span></td>
                                    <td><span class="badge bg-success">نشط</span></td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm">تعديل</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Activity Section -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sync-alt text-primary me-2"></i>
                        نشاط النظام
                    </h5>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon bg-success">
                                <i class="fas fa-power-off"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">تم تشغيل النظام</div>
                                <div class="activity-time">قبل 5 دقائق</div>
                                <div class="activity-version">الإصدار: 1.6.8.7</div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon bg-info">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">تسجيل متجر جديد</div>
                                <div class="activity-time">قبل 15 دقيقة</div>
                                <div class="activity-version">الإصدار: 2.9.87</div>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon bg-warning">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">إضافة مستفيد جديد</div>
                                <div class="activity-time">قبل 30 دقيقة</div>
                                <div class="activity-version">الإصدار: 1.2.3</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshData() {
    const button = event.target;
    const originalContent = button.innerHTML;
    
    button.innerHTML = '<span class="loading-spinner me-2"></span>جاري التحديث...';
    button.disabled = true;
    
    // Simulate refresh
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Additional styles for activity items
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .activity-item:hover {
            background: var(--gray-100);
            transform: translateX(5px);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-bottom: 0.25rem;
        }

        .activity-version {
            font-size: 0.75rem;
            color: var(--gray-400);
            font-family: monospace;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
