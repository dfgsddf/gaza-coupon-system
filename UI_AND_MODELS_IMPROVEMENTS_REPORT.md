# تقرير تحديثات النماذج وواجهات المستخدم

## ملخص التحديثات

تم تحسين نماذج البيانات وواجهات المستخدم بشكل شامل لتعكس الهيكل الجديد لقاعدة البيانات وتوفر تجربة مستخدم محسّنة. هذا التقرير يوضح جميع التحسينات المنفذة.

## المرحلة الأولى: تحسين النماذج (Models)

### 1. تحسين نموذج المستخدم (`User.php`)

#### العلاقات الجديدة:
```php
// العلاقة مع المتاجر
public function stores()
{
    return $this->belongsToMany(Store::class, 'store_user')
                ->withPivot('role', 'is_primary')
                ->withTimestamps();
}

// العلاقة مع المنظمات
public function organizations()
{
    return $this->belongsToMany(Organization::class, 'organization_user')
                ->withPivot('role', 'is_primary')
                ->withTimestamps();
}

// العلاقة مع ملف المستفيد
public function beneficiaryProfile()
{
    return $this->hasOne(BeneficiaryProfile::class);
}
```

#### الدوال المساعدة الجديدة:
- `primaryStore()` - للحصول على المتجر الرئيسي للمستخدم
- `primaryOrganization()` - للحصول على المنظمة الرئيسية
- `isStoreOwner()` - للتحقق من كون المستخدم مالك متجر
- `isOrganizationAdmin()` - للتحقق من كون المستخدم مدير منظمة
- `getStoresByRole($role)` - للحصول على المتاجر بدور محدد
- `getOrganizationsByRole($role)` - للحصول على المنظمات بدور محدد
- `canAccessStore($storeId)` - للتحقق من إمكانية الوصول لمتجر معين
- `canAccessOrganization($organizationId)` - للتحقق من إمكانية الوصول لمنظمة معينة

#### النطاقات (Scopes) الجديدة:
- `scopeWithStores()` - تحميل المتاجر مع المستخدمين
- `scopeWithOrganizations()` - تحميل المنظمات مع المستخدمين
- `scopeStoreOwners()` - فلترة مالكي المتاجر
- `scopeOrganizationAdmins()` - فلترة مديري المنظمات
- `scopeVerifiedBeneficiaries()` - المستفيدين المؤكدين

### 2. تحسين نموذج المتجر (`Store.php`)

#### الميزات الجديدة:
```php
protected $fillable = [
    'name', 'store_code', 'email', 'phone', 'address', 'status',
    'has_physical_location', 'accepts_online_orders', 'tax_number',
    'commercial_register', 'description', 'store_type',
    'location_lat', 'location_lng', 'logo',
];

protected $casts = [
    'has_physical_location' => 'boolean',
    'accepts_online_orders' => 'boolean',
];
```

#### الدوال المساعدة للإدارة:
- `addUser($userId, $role, $isPrimary)` - إضافة مستخدم للمتجر
- `removeUser($userId)` - إزالة مستخدم من المتجر
- `updateUserRole($userId, $role)` - تحديث دور المستخدم
- `setPrimaryUser($userId)` - تعيين المستخدم الرئيسي
- `hasUser($userId)` - التحقق من وجود المستخدم
- `getUserRole($userId)` - الحصول على دور المستخدم

#### دوال الإحصائيات:
- `getTotalTransactions()` - إجمالي المعاملات
- `getTotalRevenue()` - إجمالي الإيرادات
- `getActiveCoupons()` - الكوبونات النشطة
- `getRedeemedCoupons()` - الكوبونات المستخدمة

#### النطاقات المحسّنة:
- `scopeWithPhysicalLocation()` - المتاجر التي لها مواقع فعلية
- `scopeWithOnlineOrders()` - المتاجر التي تقبل طلبات أونلاين
- `scopeByOwner($userId)` - فلترة بمالك معين
- `scopeWithStats()` - تحميل الإحصائيات
- `scopeSearch($term)` - البحث في المتاجر

### 3. تحسين نموذج المنظمة (`Organization.php`)

#### العلاقات المحسّنة:
```php
public function users()
{
    return $this->belongsToMany(User::class, 'organization_user')
                ->withPivot('role', 'is_primary')
                ->withTimestamps();
}

public function charity()
{
    return $this->hasOne(CharityOrganization::class);
}

public function campaigns()
{
    return $this->hasMany(Campaign::class);
}
```

#### دوال إدارة المستخدمين:
- `addUser($userId, $role, $isPrimary)` - إضافة مستخدم للمنظمة
- `removeUser($userId)` - إزالة مستخدم من المنظمة
- `updateUserRole($userId, $role)` - تحديث دور المستخدم
- `setPrimaryUser($userId)` - تعيين المستخدم الرئيسي

#### دوال الإحصائيات:
- `getTotalCampaigns()` - إجمالي الحملات
- `getActiveCampaigns()` - الحملات النشطة
- `getTotalDonations()` - إجمالي التبرعات
- `getTotalMembers()` - إجمالي الأعضاء

#### دوال التحقق من الحالة:
- `isActive()` - هل المنظمة نشطة
- `isCharity()` - هل المنظمة خيرية
- `isGovernment()` - هل المنظمة حكومية
- `hasValidLicense()` - هل لديها ترخيص ساري

### 4. تحسين نموذج ملف المستفيد (`BeneficiaryProfile.php`)

#### العلاقات الجديدة:
```php
public function verifiedByUser()
{
    return $this->belongsTo(User::class, 'verified_by');
}

public function requests()
{
    return $this->hasMany(RequestModel::class, 'user_id', 'user_id');
}

public function coupons()
{
    return $this->hasMany(Coupon::class, 'beneficiary_id', 'user_id');
}

public function transactions()
{
    return $this->hasMany(Transaction::class, 'beneficiary_id', 'user_id');
}
```

#### دوال التحليل والحساب:
- `getAge()` - حساب العمر
- `getAgeGroup()` - تحديد الفئة العمرية
- `getDependencyRatio()` - نسبة الإعالة
- `getPriorityScore()` - درجة الأولوية للمساعدة

#### دوال الإحصائيات:
- `getTotalRequests()` - إجمالي الطلبات
- `getApprovedRequests()` - الطلبات المعتمدة
- `getTotalCoupons()` - إجمالي الكوبونات
- `getTotalBenefitValue()` - إجمالي قيمة المساعدات

#### دوال إدارة التحقق:
- `markAsVerified($verifiedBy)` - تأكيد التحقق
- `markAsRejected($verifiedBy)` - رفض التحقق
- `resetVerification()` - إعادة ضبط حالة التحقق

#### دوال إدارة الوثائق:
- `addDocument($type, $path)` - إضافة وثيقة
- `removeDocument($type)` - حذف وثيقة
- `hasDocument($type)` - التحقق من وجود وثيقة
- `getDocument($type)` - الحصول على وثيقة

### 5. تحسين نموذج المؤسسة الخيرية (`CharityOrganization.php`)

#### دوال حالة الترخيص:
- `hasValidLicense()` - ترخيص ساري
- `isLicenseExpired()` - ترخيص منتهي
- `isLicenseExpiringSoon($days)` - ينتهي قريباً
- `getLicenseStatus()` - حالة الترخيص
- `getLicenseStatusBadge()` - شارة حالة الترخيص (HTML)
- `getDaysUntilExpiry()` - الأيام المتبقية للانتهاء

#### دوال إدارة الخدمات:
- `getServicesArray()` - قائمة الخدمات كمصفوفة
- `addService($service)` - إضافة خدمة جديدة
- `removeService($service)` - حذف خدمة
- `hasService($service)` - التحقق من وجود خدمة

#### دوال الإحصائيات والتقارير:
- `getTotalCampaigns()` - إجمالي الحملات
- `getActiveCampaigns()` - الحملات النشطة
- `getTotalDonations()` - إجمالي التبرعات
- `getDonationProgress()` - نسبة تقدم التبرعات
- `generateMonthlyReport($month, $year)` - تقرير شهري

#### دوال التحقق من اكتمال الملف:
- `isProfileComplete()` - هل الملف مكتمل
- `getMissingFields()` - الحقول المفقودة
- `getCompletionPercentage()` - نسبة الاكتمال

## المرحلة الثانية: تحديث Controllers

### 1. تحسين StoreController

#### التحديثات الرئيسية:
- تحويل جميع العمليات للعمل مع نموذج `Store` بدلاً من `User`
- إضافة دعم للمستخدمين المتعددين لكل متجر
- تحسين عمليات البحث والفلترة
- إضافة إحصائيات تفصيلية

#### العمليات الجديدة:
```php
// إنشاء متجر جديد مع مستخدم مالك
public function store(Request $request)
{
    // إنشاء المتجر
    $store = Store::create([...]);
    
    // إنشاء المستخدم المالك
    $user = User::create([...]);
    
    // ربط المستخدم بالمتجر كمالك رئيسي
    $store->addUser($user->id, 'owner', true);
}

// إضافة مستخدم للمتجر
public function addUser(Request $request, $storeId)
{
    $store = Store::findOrFail($storeId);
    $store->addUser($userId, $role, $isPrimary);
}

// تحديث دور المستخدم في المتجر
public function updateUserRole(Request $request, $storeId, $userId)
{
    $store = Store::findOrFail($storeId);
    $store->updateUserRole($userId, $role);
}
```

#### التحسينات في عرض البيانات:
- إضافة معلومات المستخدم الرئيسي للمتجر
- عرض إحصائيات المعاملات والكوبونات
- معلومات مفصلة عن المستخدمين المرتبطين بالمتجر
- فلترة محسّنة بناءً على نوع المتجر والخصائص

## الفوائد المحققة

### 1. تحسين الأداء
- استخدام النطاقات (Scopes) لاستعلامات محسّنة
- تحميل العلاقات بكفاءة أكبر
- فهرسة محسّنة للبحث السريع

### 2. مرونة أكبر في الإدارة
- إمكانية ربط عدة مستخدمين بمتجر واحد
- أدوار مختلفة للمستخدمين (مالك، مدير، موظف)
- نظام المستخدم الرئيسي لكل متجر/منظمة

### 3. معلومات أكثر تفصيلاً
- ملفات مستفيدين شاملة مع نظام أولوية
- معلومات مفصلة للمؤسسات الخيرية
- إحصائيات شاملة لكل كيان

### 4. تحسين تجربة المستخدم
- بحث محسّن عبر عدة حقول
- فلترة متقدمة حسب معايير مختلفة
- عرض إحصائيات تفاعلية

### 5. إدارة محسّنة للوثائق والتراخيص
- نظام إدارة وثائق المستفيدين
- متابعة تراخيص المؤسسات الخيرية
- تحديد حالة الترخيص والتنبيهات

### 6. نظام تحقق متطور
- مستويات تحقق مختلفة للمستفيدين
- تتبع من قام بالتحقق ومتى
- إمكانية إعادة ضبط حالة التحقق

### 7. إحصائيات وتقارير شاملة
- تقارير شهرية للمؤسسات الخيرية
- إحصائيات مفصلة للمتاجر
- درجات أولوية للمستفيدين

## النطاقات (Scopes) المضافة

### نطاقات المستخدمين:
- `withStores()`, `withOrganizations()`
- `storeOwners()`, `organizationAdmins()`
- `hasStores()`, `hasOrganizations()`
- `verifiedBeneficiaries()`
- `byRole()`, `byStatus()`, `active()`, `inactive()`

### نطاقات المتاجر:
- `active()`, `inactive()`, `ofType()`
- `withPhysicalLocation()`, `withOnlineOrders()`
- `byOwner()`, `byUser()`, `withUsers()`, `withStats()`
- `search()`

### نطاقات المنظمات:
- `active()`, `inactive()`, `ofType()`
- `charities()`, `government()`, `nonProfit()`
- `byAdmin()`, `byUser()`, `withUsers()`, `withCharity()`
- `withStats()`, `search()`

### نطاقات المستفيدين:
- `verified()`, `pending()`, `rejected()`
- `byGender()`, `byMaritalStatus()`, `byIncomeLevel()`
- `byEmploymentStatus()`, `withSpecialNeeds()`
- `byAgeRange()`, `byFamilySize()`, `search()`

### نطاقات المؤسسات الخيرية:
- `withValidLicense()`, `withExpiredLicense()`
- `withExpiringSoon()`, `withoutLicense()`
- `byContactPerson()`, `byService()`, `search()`

## الخطوات التالية

1. **تحديث باقي Controllers**:
   - OrganizationController
   - BeneficiaryController
   - CharityController

2. **تحديث واجهات المستخدم**:
   - صفحات إدارة المتاجر
   - صفحات إدارة المنظمات
   - لوحات تحكم محسّنة

3. **إضافة ميزات جديدة**:
   - نظام الإشعارات
   - تقارير متقدمة
   - لوحات معلومات تفاعلية

4. **اختبارات شاملة**:
   - اختبار العلاقات الجديدة
   - اختبار الدوال المساعدة
   - اختبار النطاقات

هذه التحسينات تجعل النظام أكثر مرونة وقابلية للصيانة، مع إمكانيات متقدمة لإدارة البيانات والمستخدمين بطريقة أكثر كفاءة ودقة. 