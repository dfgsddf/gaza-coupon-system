@extends('layouts.app')

@section('title', 'إدارة المتاجر')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- رأس الصفحة -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0">
                    <i class="fas fa-store text-primary"></i>
                    إدارة المتاجر
                </h2>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStoreModal">
                        <i class="fas fa-plus"></i>
                        إضافة متجر جديد
                    </button>
                    
                    <button type="button" class="btn btn-info" id="statsBtn">
                        <i class="fas fa-chart-bar"></i>
                        الإحصائيات
                    </button>
                    
                    <button type="button" class="btn btn-secondary" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i>
                        تحديث
                    </button>
                </div>
            </div>

            <!-- بطاقات الإحصائيات -->
            <div class="row mb-4" id="statsCards" style="display: none;">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title" id="totalStores">0</h4>
                                    <p class="card-text">إجمالي المتاجر</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-store fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title" id="activeStores">0</h4>
                                    <p class="card-text">المتاجر النشطة</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title" id="physicalStores">0</h4>
                                    <p class="card-text">متاجر فعلية</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-building fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title" id="onlineStores">0</h4>
                                    <p class="card-text">متاجر أونلاين</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-globe fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- أدوات البحث والفلترة -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-filter"></i>
                        فلاتر البحث
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="searchInput" class="form-label">البحث</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="searchInput" 
                                   placeholder="اسم المتجر، الإيميل، الرمز...">
                        </div>
                        
                        <div class="col-md-2">
                            <label for="statusFilter" class="form-label">الحالة</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">جميع الحالات</option>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="storeTypeFilter" class="form-label">نوع المتجر</label>
                            <select class="form-select" id="storeTypeFilter">
                                <option value="">جميع الأنواع</option>
                                <option value="grocery">بقالة</option>
                                <option value="pharmacy">صيدلية</option>
                                <option value="restaurant">مطعم</option>
                                <option value="clothing">ملابس</option>
                                <option value="electronics">إلكترونيات</option>
                                <option value="other">أخرى</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="locationFilter" class="form-label">الموقع</label>
                            <select class="form-select" id="locationFilter">
                                <option value="">جميع المواقع</option>
                                <option value="1">له موقع فعلي</option>
                                <option value="0">بدون موقع فعلي</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="onlineFilter" class="form-label">الطلبات الأونلاين</label>
                            <select class="form-select" id="onlineFilter">
                                <option value="">الكل</option>
                                <option value="1">يقبل أونلاين</option>
                                <option value="0">لا يقبل أونلاين</option>
                            </select>
                        </div>
                        
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-secondary w-100" id="clearFilters">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جدول المتاجر -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">قائمة المتاجر</h5>
                    <span class="badge bg-primary" id="storesCount">0 متجر</span>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>رمز المتجر</th>
                                    <th>اسم المتجر</th>
                                    <th>المالك الرئيسي</th>
                                    <th>معلومات الاتصال</th>
                                    <th>النوع</th>
                                    <th>الخصائص</th>
                                    <th>الإحصائيات</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="storesTableBody">
                                <!-- سيتم ملء البيانات هنا بواسطة JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال إضافة متجر جديد -->
<div class="modal fade" id="addStoreModal" tabindex="-1" aria-labelledby="addStoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStoreModalLabel">إضافة متجر جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="addStoreForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- معلومات المتجر -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">معلومات المتجر</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="storeName" class="form-label">اسم المتجر *</label>
                                <input type="text" class="form-control" id="storeName" name="name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="storeCode" class="form-label">رمز المتجر</label>
                                <input type="text" class="form-control" id="storeCode" name="store_code" 
                                       placeholder="سيتم إنشاؤه تلقائياً إذا ترك فارغاً">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="storeType" class="form-label">نوع المتجر</label>
                                <select class="form-select" id="storeType" name="store_type">
                                    <option value="">اختر النوع</option>
                                    <option value="grocery">بقالة</option>
                                    <option value="pharmacy">صيدلية</option>
                                    <option value="restaurant">مطعم</option>
                                    <option value="clothing">ملابس</option>
                                    <option value="electronics">إلكترونيات</option>
                                    <option value="other">أخرى</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="storeStatus" class="form-label">حالة المتجر</label>
                                <select class="form-select" id="storeStatus" name="status">
                                    <option value="active" selected>نشط</option>
                                    <option value="inactive">غير نشط</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="storeDescription" class="form-label">وصف المتجر</label>
                                <textarea class="form-control" id="storeDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <!-- معلومات الاتصال -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3 mt-3">معلومات الاتصال</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="storeEmail" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="storeEmail" name="email">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="storePhone" class="form-label">رقم الهاتف</label>
                                <input type="tel" class="form-control" id="storePhone" name="phone">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="storeAddress" class="form-label">العنوان</label>
                                <textarea class="form-control" id="storeAddress" name="address" rows="2"></textarea>
                            </div>
                        </div>
                        
                        <!-- خصائص المتجر -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3 mt-3">خصائص المتجر</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="hasPhysicalLocation" 
                                       name="has_physical_location" value="1" checked>
                                <label class="form-check-label" for="hasPhysicalLocation">
                                    له موقع فعلي
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="acceptsOnlineOrders" 
                                       name="accepts_online_orders" value="1">
                                <label class="form-check-label" for="acceptsOnlineOrders">
                                    يقبل طلبات أونلاين
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taxNumber" class="form-label">الرقم الضريبي</label>
                                <input type="text" class="form-control" id="taxNumber" name="tax_number">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="commercialRegister" class="form-label">رقم السجل التجاري</label>
                                <input type="text" class="form-control" id="commercialRegister" name="commercial_register">
                            </div>
                        </div>
                        
                        <!-- معلومات المالك -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3 mt-3">معلومات المالك الرئيسي</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userName" class="form-label">اسم المالك *</label>
                                <input type="text" class="form-control" id="userName" name="user_name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userEmail" class="form-label">إيميل المالك *</label>
                                <input type="email" class="form-control" id="userEmail" name="user_email" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userPassword" class="form-label">كلمة المرور *</label>
                                <input type="password" class="form-control" id="userPassword" name="user_password" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">إضافة المتجر</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- مودال عرض تفاصيل المتجر -->
<div class="modal fade" id="viewStoreModal" tabindex="-1" aria-labelledby="viewStoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewStoreModalLabel">تفاصيل المتجر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="storeDetailsContent">
                <!-- سيتم ملء التفاصيل هنا -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إعداد CSRF token للطلبات
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // إعداد fetch بـ CSRF token وتضمين الكوكيز
    const fetchWithCSRF = (url, options = {}) => {
        const defaultHeaders = {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        };
        
        // إذا لم يكن هناك FormData، أضف Content-Type
        if (!(options.body instanceof FormData)) {
            defaultHeaders['Content-Type'] = 'application/json';
        }
        
        return fetch(url, {
            credentials: 'same-origin', // تضمين الكوكيز للمصادقة
            ...options,
            headers: {
                ...defaultHeaders,
                ...options.headers
            }
        });
    };

    let stores = [];
    let filteredStores = [];

    // تحميل البيانات
    loadStores();
    loadStats();

    // إعداد أحداث الفلترة
    setupFilterEvents();

    function loadStores() {
        console.log('Starting to load stores...');
        console.log('CSRF Token:', csrfToken);
        
        fetchWithCSRF('/admin/stores/api')
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Stores data received:', data);
                console.log('Data type:', typeof data);
                console.log('Is array:', Array.isArray(data));
                console.log('Data length:', data.length);
                
                if (Array.isArray(data)) {
                    stores = data;
                    filteredStores = [...stores];
                    displayStores(filteredStores);
                    updateStoresCount();
                } else {
                    console.error('Expected array but got:', typeof data);
                    console.error('Full data:', data);
                    showToast('خطأ في تحميل البيانات', 'error');
                }
            })
            .catch(error => {
                console.error('Error loading stores:', error);
                
                // إضافة رسالة خطأ مفصلة
                let errorMessage = 'فشل في تحميل بيانات المتاجر';
                if (error.message.includes('401')) {
                    errorMessage = 'غير مصرح لك بالوصول - يرجى تسجيل الدخول مرة أخرى';
                } else if (error.message.includes('403')) {
                    errorMessage = 'ليس لديك صلاحية للوصول إلى هذه البيانات';
                } else if (error.message.includes('500')) {
                    errorMessage = 'خطأ في الخادم - يرجى المحاولة لاحقاً';
                }
                
                showToast(errorMessage, 'error');
                
                // عرض رسالة في الجدول
                const tbody = document.getElementById('storesTableBody');
                if (tbody) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-danger py-4">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3 d-block"></i>
                                ${errorMessage}
                                <br><small class="text-muted">تفاصيل الخطأ: ${error.message}</small>
                            </td>
                        </tr>
                    `;
                }
            });
    }

    function loadStats() {
        console.log('Loading stats...');
        fetchWithCSRF('/admin/stores/stats')
            .then(response => {
                console.log('Stats response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Stats data received:', data);
                updateStatsCards(data);
            })
            .catch(error => {
                console.error('Error loading stats:', error);
            });
    }

    function updateStatsCards(stats) {
        document.getElementById('totalStores').textContent = stats.total || 0;
        document.getElementById('activeStores').textContent = stats.active || 0;
        document.getElementById('physicalStores').textContent = stats.with_physical_location || 0;
        document.getElementById('onlineStores').textContent = stats.with_online_orders || 0;
    }

    function displayStores(storesToDisplay) {
        const tbody = document.getElementById('storesTableBody');
        
        if (!storesToDisplay || storesToDisplay.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-store fa-3x mb-3 d-block"></i>
                        لا توجد متاجر لعرضها
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = storesToDisplay.map(store => {
            const primaryUser = store.primary_user;
            const statusBadge = getStatusBadge(store.status);
            const locationBadge = store.has_physical_location ? 
                '<span class="badge bg-success me-1">موقع فعلي</span>' : '';
            const onlineBadge = store.accepts_online_orders ? 
                '<span class="badge bg-info">أونلاين</span>' : '';

            return `
                <tr>
                    <td>
                        <strong>${store.store_code || 'غير محدد'}</strong>
                    </td>
                    <td>
                        <div>
                            <strong>${store.name}</strong>
                            ${store.store_type ? `<br><small class="text-muted">${getStoreTypeLabel(store.store_type)}</small>` : ''}
                        </div>
                    </td>
                    <td>
                        ${primaryUser ? `
                            <div>
                                <strong>${primaryUser.name}</strong><br>
                                <small class="text-muted">${primaryUser.email}</small>
                            </div>
                        ` : '<span class="text-muted">غير محدد</span>'}
                    </td>
                    <td>
                        <div>
                            ${store.phone ? `<i class="fas fa-phone me-1"></i>${store.phone}<br>` : ''}
                            ${store.email ? `<i class="fas fa-envelope me-1"></i>${store.email}` : ''}
                        </div>
                    </td>
                    <td>
                        ${store.store_type ? getStoreTypeLabel(store.store_type) : 'غير محدد'}
                    </td>
                    <td>
                        ${locationBadge}
                        ${onlineBadge}
                    </td>
                    <td>
                        <small>
                            المعاملات: ${store.transactions_count || 0}<br>
                            الكوبونات: ${store.coupons_count || 0}<br>
                            المستخدمين: ${store.users_count || 0}
                        </small>
                    </td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                    type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cogs"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="viewStore(${store.id})">
                                        <i class="fas fa-eye me-2"></i>عرض التفاصيل
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="editStore(${store.id})">
                                        <i class="fas fa-edit me-2"></i>تعديل
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteStore(${store.id})">
                                        <i class="fas fa-trash me-2"></i>حذف
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function setupFilterEvents() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const storeTypeFilter = document.getElementById('storeTypeFilter');
        const locationFilter = document.getElementById('locationFilter');
        const onlineFilter = document.getElementById('onlineFilter');
        const clearFilters = document.getElementById('clearFilters');

        // البحث النصي
        searchInput.addEventListener('input', applyFilters);
        
        // الفلاتر
        statusFilter.addEventListener('change', applyFilters);
        storeTypeFilter.addEventListener('change', applyFilters);
        locationFilter.addEventListener('change', applyFilters);
        onlineFilter.addEventListener('change', applyFilters);

        // مسح الفلاتر
        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            storeTypeFilter.value = '';
            locationFilter.value = '';
            onlineFilter.value = '';
            applyFilters();
        });

        // إظهار/إخفاء الإحصائيات
        document.getElementById('statsBtn').addEventListener('click', function() {
            const statsCards = document.getElementById('statsCards');
            statsCards.style.display = statsCards.style.display === 'none' ? 'flex' : 'none';
        });
    }

    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const storeTypeFilter = document.getElementById('storeTypeFilter').value;
        const locationFilter = document.getElementById('locationFilter').value;
        const onlineFilter = document.getElementById('onlineFilter').value;

        filteredStores = stores.filter(store => {
            // البحث النصي
            if (searchTerm) {
                const searchFields = [
                    store.name,
                    store.store_code,
                    store.email,
                    store.phone,
                    store.primary_user?.name,
                    store.primary_user?.email
                ].filter(Boolean).join(' ').toLowerCase();
                
                if (!searchFields.includes(searchTerm)) {
                    return false;
                }
            }

            // فلتر الحالة
            if (statusFilter && store.status !== statusFilter) {
                return false;
            }

            // فلتر نوع المتجر
            if (storeTypeFilter && store.store_type !== storeTypeFilter) {
                return false;
            }

            // فلتر الموقع الفعلي
            if (locationFilter !== '') {
                const hasLocation = locationFilter === '1';
                if (store.has_physical_location !== hasLocation) {
                    return false;
                }
            }

            // فلتر الطلبات الأونلاين
            if (onlineFilter !== '') {
                const acceptsOnline = onlineFilter === '1';
                if (store.accepts_online_orders !== acceptsOnline) {
                    return false;
                }
            }

            return true;
        });

        displayStores(filteredStores);
        updateStoresCount();
    }

    function updateStoresCount() {
        const count = filteredStores.length;
        document.getElementById('storesCount').textContent = `${count} متجر`;
    }

    // إرسال نموذج إضافة متجر
    document.getElementById('addStoreForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // التحقق من كلمة المرور
        if (formData.get('user_password').length < 8) {
            showToast('كلمة المرور يجب أن تكون 8 أحرف على الأقل', 'error');
            return;
        }
        
        fetchWithCSRF('/admin/stores/api', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('تم إضافة المتجر بنجاح', 'success');
                document.getElementById('addStoreForm').reset();
                bootstrap.Modal.getInstance(document.getElementById('addStoreModal')).hide();
                loadStores();
                loadStats();
            } else {
                showToast(data.message || 'حدث خطأ أثناء إضافة المتجر', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('خطأ في الشبكة', 'error');
        });
    });

    // دوال مساعدة
    function getStatusBadge(status) {
        switch(status) {
            case 'active':
                return '<span class="badge bg-success">نشط</span>';
            case 'inactive':
                return '<span class="badge bg-secondary">غير نشط</span>';
            default:
                return '<span class="badge bg-warning">غير محدد</span>';
        }
    }

    function getStoreTypeLabel(type) {
        const types = {
            'grocery': 'بقالة',
            'pharmacy': 'صيدلية',
            'restaurant': 'مطعم',
            'clothing': 'ملابس',
            'electronics': 'إلكترونيات',
            'other': 'أخرى'
        };
        return types[type] || type;
    }

    function showToast(message, type = 'info') {
        // يمكن استخدام مكتبة التوست المفضلة لديك
        console.log(`${type.toUpperCase()}: ${message}`);
        alert(message); // مؤقت - يُفضل استخدام نظام توست أفضل
    }

    // دوال عامة للإجراءات
    window.viewStore = function(storeId) {
        fetchWithCSRF(`/admin/stores/api/${storeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStoreDetails(data.store);
                } else {
                    showToast('فشل في تحميل تفاصيل المتجر', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('خطأ في تحميل التفاصيل', 'error');
            });
    };

    window.editStore = function(storeId) {
        // TODO: تنفيذ نموذج التعديل
        showToast('ميزة التعديل قيد التطوير', 'info');
    };

    window.deleteStore = function(storeId) {
        if (confirm('هل أنت متأكد من حذف هذا المتجر؟')) {
            fetchWithCSRF(`/admin/stores/api/${storeId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('تم حذف المتجر بنجاح', 'success');
                    loadStores();
                    loadStats();
                } else {
                    showToast(data.message || 'فشل في حذف المتجر', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('خطأ في حذف المتجر', 'error');
            });
        }
    };

    function showStoreDetails(store) {
        const content = document.getElementById('storeDetailsContent');
        content.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>معلومات المتجر</h6>
                    <table class="table table-sm">
                        <tr><td><strong>الاسم:</strong></td><td>${store.name}</td></tr>
                        <tr><td><strong>الرمز:</strong></td><td>${store.store_code || 'غير محدد'}</td></tr>
                        <tr><td><strong>النوع:</strong></td><td>${getStoreTypeLabel(store.store_type || '')}</td></tr>
                        <tr><td><strong>الحالة:</strong></td><td>${getStatusBadge(store.status)}</td></tr>
                        <tr><td><strong>الوصف:</strong></td><td>${store.description || 'غير محدد'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>الإحصائيات</h6>
                    <table class="table table-sm">
                        <tr><td><strong>إجمالي المعاملات:</strong></td><td>${store.stats.total_transactions}</td></tr>
                        <tr><td><strong>إجمالي الإيرادات:</strong></td><td>${store.stats.total_revenue} ريال</td></tr>
                        <tr><td><strong>الكوبونات النشطة:</strong></td><td>${store.stats.active_coupons}</td></tr>
                        <tr><td><strong>الكوبونات المستخدمة:</strong></td><td>${store.stats.redeemed_coupons}</td></tr>
                        <tr><td><strong>عدد المستخدمين:</strong></td><td>${store.stats.users_count}</td></tr>
                    </table>
                </div>
            </div>
            
            <hr>
            
            <div class="row">
                <div class="col-12">
                    <h6>المستخدمون المرتبطون</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الدور</th>
                                    <th>رئيسي</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${store.users.map(user => `
                                    <tr>
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.role_in_store}</td>
                                        <td>${user.is_primary ? '<i class="fas fa-check text-success"></i>' : ''}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
        
        new bootstrap.Modal(document.getElementById('viewStoreModal')).show();
    }
});
</script>
@endsection 