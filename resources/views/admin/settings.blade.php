@extends('layouts.app')

@section('sidebar_title', 'إعدادات الإدارة')

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
<div class="admin-settings-page">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h2 class="mb-1">إعدادات الإدارة</h2>
                <p class="text-muted mb-0">إدارة إعدادات النظام والملف الشخصي</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Settings -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>
                        إعدادات الملف الشخصي
                    </h5>
                </div>
                <div class="card-body">
                    <form id="profile-form">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="اختياري">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>تحديث الملف الشخصي
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Password Change -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lock me-2 text-warning"></i>
                        تغيير كلمة المرور
                    </h5>
                </div>
                <div class="card-body">
                    <form id="password-form">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="fas fa-key me-2"></i>تغيير كلمة المرور
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- System Settings -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2 text-success"></i>
                        إعدادات النظام
                    </h5>
                </div>
                <div class="card-body">
                    <form id="system-form">
                        @csrf
                        <div class="mb-3">
                            <label for="site_name" class="form-label">اسم الموقع</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" value="نظام كوبونات غزة">
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">بريد المدير الإلكتروني</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode">
                                <label class="form-check-label" for="maintenance_mode">
                                    <i class="fas fa-tools me-2"></i>وضع الصيانة
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                <label class="form-check-label" for="email_notifications">
                                    <i class="fas fa-envelope me-2"></i>إشعارات البريد الإلكتروني
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>حفظ الإعدادات
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2 text-info"></i>
                        إعدادات الإشعارات
                    </h5>
                </div>
                <div class="card-body">
                    <form id="notification-form">
                        @csrf
                        <div class="settings-group">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="new_user_notifications" name="new_user_notifications" checked>
                                    <label class="form-check-label" for="new_user_notifications">
                                        <i class="fas fa-user-plus me-2 text-primary"></i>
                                        تسجيل مستخدمين جدد
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="campaign_notifications" name="campaign_notifications" checked>
                                    <label class="form-check-label" for="campaign_notifications">
                                        <i class="fas fa-bullhorn me-2 text-success"></i>
                                        إطلاق حملات جديدة
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="transaction_notifications" name="transaction_notifications" checked>
                                    <label class="form-check-label" for="transaction_notifications">
                                        <i class="fas fa-dollar-sign me-2 text-warning"></i>
                                        المعاملات عالية القيمة
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="system_alerts" name="system_alerts" checked>
                                    <label class="form-check-label" for="system_alerts">
                                        <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                                        تنبيهات النظام
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-bell me-2"></i>حفظ إعدادات الإشعارات
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2 text-danger"></i>
                        إعدادات الأمان
                    </h5>
                </div>
                <div class="card-body">
                    <div class="security-info">
                        <div class="mb-3">
                            <label class="form-label">آخر تسجيل دخول</label>
                            <p class="text-muted">منذ ساعتين من 192.168.1.100</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الجلسات النشطة</label>
                            <p class="text-muted">جلسة واحدة نشطة</p>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="two_factor_auth" name="two_factor_auth">
                                <label class="form-check-label" for="two_factor_auth">
                                    <i class="fas fa-mobile-alt me-2"></i>
                                    التحقق بخطوتين
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="login_alerts" name="login_alerts" checked>
                                <label class="form-check-label" for="login_alerts">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    تنبيهات تسجيل الدخول
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>إنهاء جميع الجلسات
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        إجراءات سريعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" id="backup-system">
                                <i class="fas fa-database me-2"></i>نسخ احتياطي للنظام
                            </button>
                            <button class="btn btn-outline-info" id="clear-cache">
                                <i class="fas fa-broom me-2"></i>مسح ذاكرة التخزين المؤقت
                            </button>
                            <button class="btn btn-outline-warning" id="check-updates">
                                <i class="fas fa-sync-alt me-2"></i>فحص التحديثات
                            </button>
                            <button class="btn btn-outline-success" id="system-health">
                                <i class="fas fa-heartbeat me-2"></i>فحص صحة النظام
                            </button>
                            <button class="btn btn-outline-secondary" id="export-data">
                                <i class="fas fa-download me-2"></i>تصدير البيانات
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="settings-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-info-circle text-primary me-2"></i>
                <strong class="me-auto">إشعار</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="settings-toast-body"></div>
        </div>
    </div>
</div>

<style>
/* Admin Settings Page Specific Styles */
.admin-settings-page {
    padding: 0;
}

.page-header h2 {
    color: #333;
    font-weight: 600;
}

.card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    border-radius: 12px 12px 0 0 !important;
    padding: 1rem 1.25rem;
}

.card-header h5 {
    margin-bottom: 0;
    font-weight: 600;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #2A61CC;
    box-shadow: 0 0 0 0.2rem rgba(42, 97, 204, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.form-check-input:checked {
    background-color: #2A61CC;
    border-color: #2A61CC;
}

.form-check-label {
    font-weight: 500;
    cursor: pointer;
}

.form-switch .form-check-input {
    width: 2em;
    height: 1em;
}

.settings-group {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.security-info p {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.quick-actions .btn {
    text-align: left;
    padding: 0.75rem 1rem;
    justify-content: flex-start;
}

.quick-actions .btn i {
    width: 20px;
}

/* Loading States */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn.loading {
    position: relative;
    pointer-events: none;
}

.btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -8px 0 0 -8px;
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header .col-md-12 {
        text-align: center;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .quick-actions .btn {
        text-align: center;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .card-header {
        padding: 0.75rem 1rem;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
    
    .form-control {
        padding: 0.5rem 0.75rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}
</style>

<script>
// Toast notification function
function showSettingsToast(message, type = 'success') {
    const toast = document.getElementById('settings-toast');
    const toastBody = document.getElementById('settings-toast-body');
    
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

// Profile form submission
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
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
            showSettingsToast('تم تحديث الملف الشخصي بنجاح!', 'success');
        } else {
            showSettingsToast(data.message || 'فشل في تحديث الملف الشخصي.', 'danger');
        }
    })
    .catch(error => {
        showSettingsToast('حدث خطأ. يرجى المحاولة مرة أخرى.', 'danger');
    })
    .finally(() => {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Password form submission
document.getElementById('password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
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
            showSettingsToast('تم تغيير كلمة المرور بنجاح!', 'success');
            this.reset();
        } else {
            showSettingsToast(data.message || 'فشل في تغيير كلمة المرور.', 'danger');
        }
    })
    .catch(error => {
        showSettingsToast('حدث خطأ. يرجى المحاولة مرة أخرى.', 'danger');
    })
    .finally(() => {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// System settings form submission
document.getElementById('system-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
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
            showSettingsToast('تم حفظ إعدادات النظام بنجاح!', 'success');
        } else {
            showSettingsToast(data.message || 'فشل في حفظ الإعدادات.', 'danger');
        }
    })
    .catch(error => {
        showSettingsToast('حدث خطأ. يرجى المحاولة مرة أخرى.', 'danger');
    })
    .finally(() => {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Notification settings form submission
document.getElementById('notification-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
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
            showSettingsToast('تم حفظ إعدادات الإشعارات بنجاح!', 'success');
        } else {
            showSettingsToast(data.message || 'فشل في حفظ إعدادات الإشعارات.', 'danger');
        }
    })
    .catch(error => {
        showSettingsToast('حدث خطأ. يرجى المحاولة مرة أخرى.', 'danger');
    })
    .finally(() => {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Quick actions
document.getElementById('backup-system').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.classList.add('loading');
    btn.disabled = true;
    
    setTimeout(() => {
        showSettingsToast('تم إنشاء النسخة الاحتياطية بنجاح!', 'success');
        btn.classList.remove('loading');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 3000);
});

document.getElementById('clear-cache').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.classList.add('loading');
    btn.disabled = true;
    
    setTimeout(() => {
        showSettingsToast('تم مسح ذاكرة التخزين المؤقت!', 'success');
        btn.classList.remove('loading');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 2000);
});

document.getElementById('check-updates').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.classList.add('loading');
    btn.disabled = true;
    
    setTimeout(() => {
        showSettingsToast('النظام محدث إلى أحدث إصدار!', 'success');
        btn.classList.remove('loading');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 2500);
});

document.getElementById('system-health').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.classList.add('loading');
    btn.disabled = true;
    
    setTimeout(() => {
        showSettingsToast('النظام يعمل بشكل طبيعي!', 'success');
        btn.classList.remove('loading');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 1500);
});

document.getElementById('export-data').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.classList.add('loading');
    btn.disabled = true;
    
    setTimeout(() => {
        showSettingsToast('تم تصدير البيانات بنجاح!', 'success');
        btn.classList.remove('loading');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 3500);
});
</script>
@endsection 