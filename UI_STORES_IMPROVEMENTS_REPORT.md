# تقرير تحديثات واجهة صفحة المتاجر

## ملخص التحديثات

تم تطوير واجهة صفحة إدارة المتاجر بالكامل لتعكس الهيكل الجديد لقاعدة البيانات وتوفر تجربة مستخدم محسّنة وأكثر تفاعلية.

## قبل التحديث

### المشاكل في الواجهة القديمة:
- تعتمد على نموذج `User` مع `role = 'store'`
- عدم وجود فلاتر متقدمة
- إحصائيات بسيطة ومحدودة
- نقص في المعلومات التفصيلية
- عدم دعم المستخدمين المتعددين للمتجر الواحد
- واجهة أساسية بدون تفاعل متقدم

## بعد التحديث

### التحسينات الشاملة المنفذة:

#### 1. تصميم محسّن ومرن
```html
<!-- رأس الصفحة المحسّن -->
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
```

#### 2. بطاقات إحصائيات تفاعلية
```html
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
    <!-- بطاقات أخرى للمتاجر النشطة والمتاجر الفعلية والأونلاين -->
</div>
```

#### 3. نظام فلترة متقدم
```html
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter"></i>
            فلاتر البحث
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- البحث النصي -->
            <div class="col-md-3">
                <label for="searchInput" class="form-label">البحث</label>
                <input type="text" class="form-control" id="searchInput" 
                       placeholder="اسم المتجر، الإيميل، الرمز...">
            </div>
            
            <!-- فلتر الحالة -->
            <div class="col-md-2">
                <label for="statusFilter" class="form-label">الحالة</label>
                <select class="form-select" id="statusFilter">
                    <option value="">جميع الحالات</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
            
            <!-- فلاتر أخرى للنوع والموقع والطلبات الأونلاين -->
        </div>
    </div>
</div>
```

#### 4. جدول محسّن مع معلومات تفصيلية
```html
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
        <!-- البيانات المحسّنة هنا -->
    </tbody>
</table>
```

#### 5. نموذج إضافة متجر محسّن
##### معلومات المتجر:
- اسم المتجر (مطلوب)
- رمز المتجر (اختياري - يتم إنشاؤه تلقائياً)
- نوع المتجر (قائمة منسدلة)
- حالة المتجر
- وصف المتجر

##### معلومات الاتصال:
- البريد الإلكتروني
- رقم الهاتف
- العنوان

##### خصائص المتجر:
- له موقع فعلي (checkbox)
- يقبل طلبات أونلاين (checkbox)
- الرقم الضريبي
- رقم السجل التجاري

##### معلومات المالك الرئيسي:
- اسم المالك (مطلوب)
- إيميل المالك (مطلوب)
- كلمة المرور (مطلوب)

#### 6. مودال عرض تفاصيل المتجر
```javascript
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
}
```

#### 7. JavaScript محسّن للتفاعل

##### إدارة البيانات:
```javascript
document.addEventListener('DOMContentLoaded', function() {
    let stores = [];
    let filteredStores = [];

    // تحميل البيانات
    loadStores();
    loadStats();

    // إعداد أحداث الفلترة
    setupFilterEvents();
});
```

##### نظام الفلترة المتقدم:
```javascript
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const storeTypeFilter = document.getElementById('storeTypeFilter').value;
    const locationFilter = document.getElementById('locationFilter').value;
    const onlineFilter = document.getElementById('onlineFilter').value;

    filteredStores = stores.filter(store => {
        // البحث النصي متعدد الحقول
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

        // فلاتر متعددة للحالة والنوع والخصائص
        if (statusFilter && store.status !== statusFilter) return false;
        if (storeTypeFilter && store.store_type !== storeTypeFilter) return false;
        
        // فلتر الموقع الفعلي والطلبات الأونلاين
        if (locationFilter !== '') {
            const hasLocation = locationFilter === '1';
            if (store.has_physical_location !== hasLocation) return false;
        }
        
        if (onlineFilter !== '') {
            const acceptsOnline = onlineFilter === '1';
            if (store.accepts_online_orders !== acceptsOnline) return false;
        }

        return true;
    });

    displayStores(filteredStores);
    updateStoresCount();
}
```

##### عرض البيانات المحسّن:
```javascript
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
                <td><strong>${store.store_code || 'غير محدد'}</strong></td>
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
                <td>${store.store_type ? getStoreTypeLabel(store.store_type) : 'غير محدد'}</td>
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
```

## الفوائد المحققة

### 1. تجربة مستخدم محسّنة
- واجهة أكثر تفاعلية وسهولة في الاستخدام
- تصميم عصري ومتجاوب
- إظهار المعلومات بشكل منظم وواضح

### 2. فلترة وبحث متقدم
- بحث متعدد الحقول في الوقت الفعلي
- فلاتر متنوعة (الحالة، النوع، الخصائص)
- إمكانية مسح الفلاتر بنقرة واحدة

### 3. إحصائيات تفاعلية
- بطاقات إحصائيات قابلة للإظهار/الإخفاء
- معلومات شاملة عن المتاجر
- تحديث فوري للإحصائيات

### 4. معلومات شاملة
- عرض المستخدم الرئيسي لكل متجر
- إحصائيات المعاملات والكوبونات
- خصائص المتجر (موقع فعلي، أونلاين)
- معلومات الاتصال والوثائق

### 5. إدارة محسّنة
- نموذج إضافة شامل مع التحقق من البيانات
- عرض تفاصيل كاملة للمتجر
- قائمة إجراءات منظمة (عرض، تعديل، حذف)

### 6. أداء محسّن
- تحميل البيانات بكفاءة
- فلترة من جهة العميل للاستجابة السريعة
- تحديث ديناميكي للمحتوى

### 7. دعم للهيكل الجديد
- التكامل مع نموذج `Store` الجديد
- دعم العلاقات متعددة المستخدمين
- استخدام الدوال المساعدة الجديدة

## التقنيات المستخدمة

### Frontend:
- **Bootstrap 5**: للتصميم المتجاوب
- **Font Awesome**: للأيقونات
- **JavaScript Vanilla**: للتفاعل والديناميكية
- **CSS Flexbox**: للتخطيط المرن

### Backend Integration:
- **Laravel API**: للتواصل مع الخادم
- **CSRF Protection**: للأمان
- **JSON Responses**: لتبادل البيانات

### UX/UI Features:
- **Real-time Search**: بحث فوري
- **Responsive Design**: متجاوب مع جميع الشاشات
- **Loading States**: حالات التحميل
- **Error Handling**: معالجة الأخطاء
- **Toast Notifications**: إشعارات المستخدم

## الميزات المستقبلية

1. **تصدير البيانات**: إمكانية تصدير قائمة المتاجر
2. **استيراد المتاجر**: رفع ملف Excel لإضافة متاجر متعددة
3. **نظام إشعارات**: تنبيهات للمتاجر الجديدة أو المعطلة
4. **لوحة معلومات**: رسوم بيانية للإحصائيات
5. **نظام الأذونات**: تحكم دقيق في صلاحيات المستخدمين
6. **تقارير مفصلة**: تقارير شاملة عن أداء المتاجر

هذه التحديثات تجعل واجهة إدارة المتاجر أكثر قوة ومرونة، وتوفر تجربة مستخدم متميزة للمديرين لإدارة المتاجر بكفاءة عالية. 