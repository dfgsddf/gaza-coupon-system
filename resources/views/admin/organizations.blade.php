@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/organizations.css') }}">
@endpush

@section('sidebar_title', 'نظام قسائم غزة')
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
                <a href="/admin/users" class="sidebar-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    إدارة المستخدمين
                </a>
            </li>
            <li>
                <a href="/admin/organizations" class="sidebar-link {{ request()->is('admin/organizations*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    الجمعيات الخيرية
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
@endsection

@section('sidebar_footer')
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-white p-0 w-100 text-start">
                <i class="fas fa-sign-out-alt me-2"></i>
                تسجيل الخروج
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header Section -->
    <div class="organizations-header">
        <div class="header-content">
            <div class="header-info">
                <h1 class="page-title">
                    <i class="fas fa-building me-3"></i>
                    إدارة الجمعيات الخيرية
                </h1>
                <p class="page-subtitle">إدارة وتنظيم الجمعيات الخيرية المسجلة في النظام</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary btn-add-organization" data-bs-toggle="modal" data-bs-target="#addOrganizationModal">
                    <i class="fas fa-plus me-2"></i>
                    إضافة جمعية جديدة
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="stats-section">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-card-primary" id="org-total-card">
                    <div class="stats-card-content">
                        <div class="stats-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number" id="org-total">0</h3>
                            <p class="stats-label">إجمالي الجمعيات</p>
                        </div>
                        <div class="stats-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>+12%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-card-success" id="org-active-card">
                    <div class="stats-card-content">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number" id="org-active">0</h3>
                            <p class="stats-label">الجمعيات النشطة</p>
                        </div>
                        <div class="stats-trend">
                            <i class="fas fa-arrow-up"></i>
                            <span>+8%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-card-warning" id="org-pending-card">
                    <div class="stats-card-content">
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number" id="org-pending">0</h3>
                            <p class="stats-label">في انتظار الموافقة</p>
                        </div>
                        <div class="stats-trend">
                            <i class="fas fa-arrow-down"></i>
                            <span>-3%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-card-danger" id="org-suspended-card">
                    <div class="stats-card-content">
                        <div class="stats-icon">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="stats-info">
                            <h3 class="stats-number" id="org-suspended">0</h3>
                            <p class="stats-label">الجمعيات المعلقة</p>
                        </div>
                        <div class="stats-trend">
                            <i class="fas fa-minus"></i>
                            <span>0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Search and Filter Section -->
    <div class="filters-section">
        <div class="filters-card">
            <div class="filters-header">
                <h5 class="filters-title">
                    <i class="fas fa-filter me-2"></i>
                    البحث والفلترة
                </h5>
            </div>
            <div class="filters-content">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="search-group">
                            <label for="search-organizations" class="form-label">
                                <i class="fas fa-search me-2"></i>
                                البحث في الجمعيات
                            </label>
                            <div class="search-input-wrapper">
                                <input type="text" class="form-control search-input" id="search-organizations" placeholder="ابحث عن جمعية...">
                                <i class="fas fa-search search-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="filter-group">
                            <label for="filter-status" class="form-label">
                                <i class="fas fa-toggle-on me-2"></i>
                                حالة الجمعية
                            </label>
                            <select class="form-select filter-select" id="filter-status">
                                <option value="">جميع الحالات</option>
                                <option value="active">نشطة</option>
                                <option value="pending">في الانتظار</option>
                                <option value="suspended">معلقة</option>
                                <option value="inactive">غير نشطة</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="filter-group">
                            <label for="filter-type" class="form-label">
                                <i class="fas fa-tag me-2"></i>
                                نوع الجمعية
                            </label>
                            <select class="form-select filter-select" id="filter-type">
                                <option value="">جميع الأنواع</option>
                                <option value="charity">جمعية خيرية</option>
                                <option value="ngo">منظمة غير حكومية</option>
                                <option value="foundation">مؤسسة</option>
                                <option value="association">جمعية</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <div class="filter-actions">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-primary btn-filter" onclick="filterOrganizations()">
                                <i class="fas fa-search me-2"></i>
                                فلترة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Organizations Table -->
    <div class="organizations-section">
        <div class="organizations-card">
            <div class="organizations-header">
                <div class="organizations-title">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        قائمة الجمعيات الخيرية
                    </h5>
                    <span class="organizations-count" id="organizations-count">0 جمعية</span>
                </div>
                <div class="organizations-actions">
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportOrganizations()">
                        <i class="fas fa-download me-2"></i>
                        تصدير
                    </button>
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshOrganizations()">
                        <i class="fas fa-sync-alt me-2"></i>
                        تحديث
                    </button>
                </div>
            </div>
            <div class="organizations-content">
                <div class="table-responsive">
                    <table class="table organizations-table" id="organizations-table">
                        <thead>
                            <tr>
                                <th class="id-col">الرقم</th>
                                <th class="organization-col">الجمعية</th>
                                <th class="type-col">النوع</th>
                                <th class="description-col">الوصف</th>
                                <th class="contact-col">معلومات الاتصال</th>
                                <th class="website-col">الموقع الإلكتروني</th>
                                <th class="status-col">الحالة</th>
                                <th class="date-col">تاريخ التسجيل</th>
                                <th class="actions-col">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="organizations-tbody">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>
                <div class="organizations-empty" id="organizations-empty" style="display: none;">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h5>لا توجد جمعيات مسجلة</h5>
                        <p>ابدأ بإضافة جمعية خيرية جديدة</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrganizationModal">
                            <i class="fas fa-plus me-2"></i>
                            إضافة جمعية جديدة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

<!-- Enhanced Add Organization Modal -->
<div class="modal fade" id="addOrganizationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    إضافة جمعية جديدة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-organization-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="org-name" class="form-label">
                                <i class="fas fa-building me-2"></i>
                                اسم الجمعية
                            </label>
                            <input type="text" class="form-control" id="org-name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="org-type" class="form-label">
                                <i class="fas fa-tag me-2"></i>
                                نوع الجمعية
                            </label>
                            <select class="form-select" id="org-type" name="type" required>
                                <option value="">اختر النوع</option>
                                <option value="charity">جمعية خيرية</option>
                                <option value="ngo">منظمة غير حكومية</option>
                                <option value="foundation">مؤسسة</option>
                                <option value="association">جمعية</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="org-email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>
                                البريد الإلكتروني
                            </label>
                            <input type="email" class="form-control" id="org-email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="org-phone" class="form-label">
                                <i class="fas fa-phone me-2"></i>
                                رقم الهاتف
                            </label>
                            <input type="tel" class="form-control" id="org-phone" name="phone" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="org-website" class="form-label">
                                <i class="fas fa-globe me-2"></i>
                                الموقع الإلكتروني
                            </label>
                            <input type="url" class="form-control" id="org-website" name="website">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="org-status" class="form-label">
                                <i class="fas fa-toggle-on me-2"></i>
                                الحالة
                            </label>
                            <select class="form-select" id="org-status" name="status" required>
                                <option value="active">نشطة</option>
                                <option value="pending">في الانتظار</option>
                                <option value="suspended">معلقة</option>
                                <option value="inactive">غير نشطة</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="org-address" class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                العنوان
                            </label>
                            <textarea class="form-control" id="org-address" name="address" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="org-description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>
                                الوصف
                            </label>
                            <textarea class="form-control" id="org-description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="saveOrganization()">
                    <i class="fas fa-save me-2"></i>
                    حفظ الجمعية
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Organization Details Modal -->
<div class="modal fade" id="organizationDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-building me-2"></i>
                    تفاصيل الجمعية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="organization-details-content">
                    <!-- Details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-warning" id="edit-organization-btn">
                    <i class="fas fa-edit me-2"></i>
                    تعديل
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced View/Edit Organization Modal -->
<div class="modal fade" id="viewEditOrganizationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEditOrganizationModalTitle">
                    <i class="fas fa-eye me-2"></i>
                    تفاصيل الجمعية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="edit-organization-form">
                    <input type="hidden" id="edit-org-id" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-name" class="form-label">
                                <i class="fas fa-building me-2"></i>
                                اسم الجمعية
                            </label>
                            <input type="text" class="form-control" id="edit-org-name" name="name" required readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-type" class="form-label">
                                <i class="fas fa-tag me-2"></i>
                                نوع الجمعية
                            </label>
                            <input type="text" class="form-control" id="edit-org-type" name="type" required readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>
                                البريد الإلكتروني
                            </label>
                            <input type="email" class="form-control" id="edit-org-email" name="email" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-phone" class="form-label">
                                <i class="fas fa-phone me-2"></i>
                                رقم الهاتف
                            </label>
                            <input type="text" class="form-control" id="edit-org-phone" name="phone" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-org-address" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            العنوان
                        </label>
                        <input type="text" class="form-control" id="edit-org-address" name="address" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-status" class="form-label">
                                <i class="fas fa-toggle-on me-2"></i>
                                الحالة
                            </label>
                            <input type="text" class="form-control" id="edit-org-status" name="status" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-org-registration-date" class="form-label">
                                <i class="fas fa-calendar me-2"></i>
                                تاريخ التسجيل
                            </label>
                            <input type="text" class="form-control" id="edit-org-registration-date" name="registration_date" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-org-description" class="form-label">
                            <i class="fas fa-align-left me-2"></i>
                            الوصف
                        </label>
                        <textarea class="form-control" id="edit-org-description" name="description" rows="3" readonly></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    إغلاق
                </button>
                <button type="button" class="btn btn-warning" id="edit-org-btn" style="display:none;" onclick="enableEditOrganization()">
                    <i class="fas fa-edit me-2"></i>
                    تعديل
                </button>
                <button type="button" class="btn btn-success" id="save-org-btn" style="display:none;" onclick="saveEditOrganization()">
                    <i class="fas fa-save me-2"></i>
                    حفظ التغييرات
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast for notifications -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="organizations-toast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="organizations-toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
function showOrganizationsToast(message, type = 'success') {
    const toast = document.getElementById('organizations-toast');
    const toastBody = document.getElementById('organizations-toast-body');
    
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toastBody.textContent = message;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

function refreshOrganizations() {
    // In a real application, this would fetch fresh data from the server
    showOrganizationsToast('تم تحديث قائمة الجمعيات!');
}

function filterOrganizations() {
    const search = document.getElementById('search-organizations').value;
    const status = document.getElementById('filter-status').value;
    const type = document.getElementById('filter-type').value;
    
    // In a real application, this would send AJAX request to filter data
    showOrganizationsToast('تم فلترة الجمعيات!');
}

function exportOrganizations() {
    showOrganizationsToast('جاري تصدير البيانات...');
}

// عرض تفاصيل الجمعية
function viewOrganization(id) {
    fetch(`/admin/organizations/api/${id}`)
        .then(response => response.json())
        .then(org => {
            const modal = new bootstrap.Modal(document.getElementById('organizationDetailsModal'));
            const content = document.getElementById('organization-details-content');
            
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <div class="organization-header mb-4">
                            <div class="d-flex align-items-center">
                                <div class="organization-avatar-large me-3">
                                    <i class="fas fa-building fa-3x text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1">${org.name}</h4>
                                    <span class="badge bg-primary fs-6">${getTypeLabel(org.type || 'charity')}</span>
                                    <span class="badge bg-${getStatusColor(org.status || 'active')} ms-2">${getStatusLabel(org.status || 'active')}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-envelope me-2 text-muted"></i>البريد الإلكتروني</h6>
                                <p class="text-muted">${org.email || 'غير متوفر'}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-phone me-2 text-muted"></i>رقم الهاتف</h6>
                                <p class="text-muted">${org.phone || 'غير متوفر'}</p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-globe me-2 text-muted"></i>الموقع الإلكتروني</h6>
                                <p class="text-muted">
                                    ${org.website ? `<a href="${org.website}" target="_blank">${org.website}</a>` : 'غير متوفر'}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6><i class="fas fa-calendar me-2 text-muted"></i>تاريخ التسجيل</h6>
                                <p class="text-muted">${org.registration_date || (org.created_at ? new Date(org.created_at).toLocaleDateString('ar-SA') : 'غير محدد')}</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fas fa-map-marker-alt me-2 text-muted"></i>العنوان</h6>
                            <p class="text-muted">${org.address || 'غير متوفر'}</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6><i class="fas fa-align-left me-2 text-muted"></i>الوصف</h6>
                            <p class="text-muted">${org.description || 'لا يوجد وصف متوفر'}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات إضافية</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>رقم الجمعية:</strong> ${org.id}
                                </div>
                                <div class="mb-3">
                                    <strong>النوع:</strong> ${getTypeLabel(org.type || 'charity')}
                                </div>
                                <div class="mb-3">
                                    <strong>الحالة:</strong> 
                                    <span class="badge bg-${getStatusColor(org.status || 'active')}">${getStatusLabel(org.status || 'active')}</span>
                                </div>
                                <div class="mb-3">
                                    <strong>تاريخ الإنشاء:</strong> ${org.created_at ? new Date(org.created_at).toLocaleDateString('ar-SA') : 'غير محدد'}
                                </div>
                                ${org.updated_at ? `<div class="mb-3">
                                    <strong>آخر تحديث:</strong> ${new Date(org.updated_at).toLocaleDateString('ar-SA')}
                                </div>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add click event to edit button
            document.getElementById('edit-organization-btn').onclick = () => {
                modal.hide();
                editOrganization(id);
            };
            
            modal.show();
        })
        .catch(() => showOrganizationsToast('خطأ في جلب تفاصيل الجمعية', 'danger'));
}

function editOrganization(id) {
    fetch(`/admin/organizations/api/${id}`)
        .then(response => response.json())
        .then(org => {
            document.getElementById('viewEditOrganizationModalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>تعديل الجمعية';
            document.getElementById('edit-org-id').value = org.id;
            document.getElementById('edit-org-name').value = org.name || '';
            document.getElementById('edit-org-type').value = org.type || '';
            document.getElementById('edit-org-email').value = org.email || '';
            document.getElementById('edit-org-phone').value = org.phone || '';
            document.getElementById('edit-org-address').value = org.address || '';
            document.getElementById('edit-org-status').value = org.status || '';
            document.getElementById('edit-org-registration-date').value = org.registration_date || (org.created_at ? org.created_at.split('T')[0] : '');
            document.getElementById('edit-org-description').value = org.description || '';
            document.getElementById('edit-org-name').readOnly = false;
            document.getElementById('edit-org-type').readOnly = false;
            document.getElementById('edit-org-email').readOnly = false;
            document.getElementById('edit-org-phone').readOnly = false;
            document.getElementById('edit-org-address').readOnly = false;
            document.getElementById('edit-org-status').readOnly = false;
            document.getElementById('edit-org-registration-date').readOnly = false;
            document.getElementById('edit-org-description').readOnly = false;
            document.getElementById('edit-org-btn').style.display = 'none';
            document.getElementById('save-org-btn').style.display = 'inline-block';
            var modal = new bootstrap.Modal(document.getElementById('viewEditOrganizationModal'));
            modal.show();
        });
}

function enableEditOrganization() {
    document.getElementById('edit-org-name').readOnly = false;
    document.getElementById('edit-org-type').readOnly = false;
    document.getElementById('edit-org-btn').style.display = 'none';
    document.getElementById('save-org-btn').style.display = 'inline-block';
}

function saveEditOrganization() {
    const id = document.getElementById('edit-org-id').value;
    const formData = new FormData(document.getElementById('edit-organization-form'));
    fetch(`/admin/organizations/api/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showOrganizationsToast('تم تحديث الجمعية بنجاح!', 'success');
        var modal = bootstrap.Modal.getInstance(document.getElementById('viewEditOrganizationModal'));
        modal.hide();
        loadOrganizations();
    })
    .catch(() => showOrganizationsToast('خطأ في تحديث الجمعية', 'danger'));
}

// جلب المنظمات من API وعرضها في الجدول
function loadOrganizations() {
    fetch('/admin/organizations/api')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('organizations-tbody');
            const emptyState = document.getElementById('organizations-empty');
            const countElement = document.getElementById('organizations-count');
            
            if (data.length === 0) {
                tbody.innerHTML = '';
                emptyState.style.display = 'block';
                countElement.textContent = '0 جمعية';
                return;
            }
            
            emptyState.style.display = 'none';
            countElement.textContent = `${data.length} جمعية`;
            
            tbody.innerHTML = '';
            data.forEach(org => {
                tbody.innerHTML += `
                    <tr class="organization-row">
                        <td class="id-cell">${org.id}</td>
                        <td class="organization-cell">
                            <div class="organization-info">
                                <div class="organization-avatar">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="organization-details">
                                    <h6 class="organization-name">${org.name}</h6>
                                    <p class="organization-email">${org.email || 'غير متوفر'}</p>
                                </div>
                            </div>
                        </td>
                        <td class="type-cell">
                            <span class="type-badge type-${org.type || 'charity'}">
                                ${getTypeLabel(org.type || 'charity')}
                            </span>
                        </td>
                        <td class="description-cell">
                            <p class="description-text">${org.description || 'غير متوفر'}</p>
                        </td>
                        <td class="contact-cell">
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>${org.phone || 'غير متوفر'}</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>${org.address || 'غير متوفر'}</span>
                                </div>
                            </div>
                        </td>
                        <td class="website-cell">
                            <a href="${org.website || '#'}" target="_blank" class="website-link">${org.website || 'غير متوفر'}</a>
                        </td>
                        <td class="status-cell">
                            <span class="status-badge status-${org.status || 'active'}">
                                ${getStatusLabel(org.status || 'active')}
                            </span>
                        </td>
                        <td class="date-cell">
                            <div class="date-info">
                                <i class="fas fa-calendar"></i>
                                <span>${org.registration_date || (org.created_at ? new Date(org.created_at).toLocaleDateString('ar-SA') : 'غير محدد')}</span>
                            </div>
                        </td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewOrganization(${org.id})" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editOrganization(${org.id})" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteOrganization(${org.id})" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        });
}

function getTypeLabel(type) {
    const types = {
        'charity': 'جمعية خيرية',
        'ngo': 'منظمة غير حكومية',
        'foundation': 'مؤسسة',
        'association': 'جمعية'
    };
    return types[type] || 'غير محدد';
}

function getStatusLabel(status) {
    const statuses = {
        'active': 'نشطة',
        'pending': 'في الانتظار',
        'suspended': 'معلقة',
        'inactive': 'غير نشطة'
    };
    return statuses[status] || 'غير محدد';
}

function getStatusColor(status) {
    const colors = {
        'active': 'success',
        'pending': 'warning',
        'suspended': 'danger',
        'inactive': 'secondary'
    };
    return colors[status] || 'secondary';
}

// إضافة منظمة جديدة عبر API
function saveOrganization() {
    const form = document.getElementById('add-organization-form');
    const formData = new FormData(form);
    fetch('/admin/organizations/api', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showOrganizationsToast('تم إضافة الجمعية بنجاح!', 'success');
        const modal = bootstrap.Modal.getInstance(document.getElementById('addOrganizationModal'));
        modal.hide();
        form.reset();
        loadOrganizations();
    })
    .catch(() => showOrganizationsToast('خطأ في إضافة الجمعية', 'danger'));
}

// حذف منظمة عبر API
function deleteOrganization(id) {
    if (confirm('هل أنت متأكد من حذف هذه الجمعية؟')) {
        fetch(`/admin/organizations/api/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(() => {
            showOrganizationsToast('تم حذف الجمعية بنجاح!', 'success');
            loadOrganizations();
        })
        .catch(() => showOrganizationsToast('خطأ في حذف الجمعية', 'danger'));
    }
}

// Make stats cards clickable for refresh
document.querySelectorAll('.stats-card').forEach(card => {
    card.addEventListener('click', function() {
        if (this.querySelector('.stats-trend')) {
            showOrganizationsToast('تم تحديث الإحصائيات!');
        }
    });
});

// Auto-refresh every 30 seconds
setInterval(function() {
    refreshOrganizations();
}, 30000);

function loadOrganizationStats() {
    fetch('/admin/organizations/stats')
        .then(response => response.json())
        .then(stats => {
            document.getElementById('org-total').textContent = stats.total;
            document.getElementById('org-active').textContent = stats.active;
            document.getElementById('org-pending').textContent = stats.pending;
            document.getElementById('org-suspended').textContent = stats.suspended;
        });
}

// تحميل المنظمات عند تحميل الصفحة
window.addEventListener('DOMContentLoaded', function() {
    loadOrganizationStats();
    loadOrganizations();
    // تحديث الإحصائيات عند الضغط على أي بطاقة
    document.getElementById('org-total-card').onclick = loadOrganizationStats;
    document.getElementById('org-active-card').onclick = loadOrganizationStats;
    document.getElementById('org-pending-card').onclick = loadOrganizationStats;
    document.getElementById('org-suspended-card').onclick = loadOrganizationStats;
});
</script>

<!-- Admin Enhancements JavaScript -->
<script src="{{ asset('assets/js/admin-enhancements.js') }}"></script>
@endsection 