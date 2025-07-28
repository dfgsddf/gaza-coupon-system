# تقرير تحديثات قاعدة البيانات

## ملخص التحديثات

تم تطوير قاعدة البيانات بشكل شامل لتحقيق الأهداف التالية:

1. **الفصل بين أنواع البيانات**:
   - فصل المتاجر عن المستخدمين
   - فصل المنظمات عن المستخدمين
   - إضافة معلومات تفصيلية للمستفيدين

2. **تقليل التكرار في البيانات**:
   - تحسين العلاقات بين الجداول
   - تطوير آلية الربط بين المستخدمين والمتاجر/المنظمات

3. **تحسين الأداء**:
   - إضافة فهارس على الحقول المهمة
   - تحسين استعلامات قواعد البيانات

## التحديثات المنفذة

### 1. إنشاء نظام متاجر منفصل

تم إنشاء جدول `stores` منفصل مع علاقات متعددة للمستخدمين:

```php
Schema::create('stores', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('store_code')->nullable();
    $table->text('description')->nullable();
    $table->boolean('has_physical_location')->default(true);
    $table->boolean('accepts_online_orders')->default(false);
    $table->string('tax_number')->nullable();
    $table->string('commercial_register')->nullable();
    $table->string('store_type')->nullable();
    $table->string('location_lat')->nullable();
    $table->string('location_lng')->nullable();
    $table->string('logo')->nullable();
    $table->timestamps();
});
```

### 2. إنشاء العلاقة بين المستخدمين والمتاجر

تم إنشاء جدول وسيط `store_user` يسمح بربط عدة مستخدمين بمتجر واحد مع تحديد الدور:

```php
Schema::create('store_user', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('store_id');
    $table->string('role')->default('owner');
    $table->boolean('is_primary')->default(false);
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
    $table->unique(['user_id', 'store_id']);
});
```

### 3. تحسين نظام المنظمات

تم تحسين جدول المنظمات وإضافة جدول وسيط لربط المستخدمين بالمنظمات:

```php
Schema::table('organizations', function (Blueprint $table) {
    $table->string('organization_code')->nullable();
    $table->string('organization_type')->nullable();
    $table->string('status')->default('active');
    $table->boolean('is_active')->default(true);
});

Schema::create('organization_user', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('organization_id');
    $table->string('role')->default('member');
    $table->boolean('is_primary')->default(false);
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
    $table->unique(['user_id', 'organization_id']);
});
```

### 4. إضافة تفاصيل للمؤسسات الخيرية

تم إنشاء جدول `charity_organizations` لتخزين معلومات تفصيلية عن المؤسسات الخيرية:

```php
Schema::create('charity_organizations', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('organization_id');
    $table->string('registration_number')->nullable();
    $table->string('license_number')->nullable();
    $table->date('license_expiry_date')->nullable();
    $table->text('mission_statement')->nullable();
    $table->text('vision_statement')->nullable();
    $table->text('services')->nullable();
    $table->string('contact_person')->nullable();
    $table->string('website')->nullable();
    $table->string('bank_account')->nullable();
    $table->string('bank_name')->nullable();
    $table->timestamps();
    
    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
});
```

### 5. تحسين نظام المستفيدين

تم إنشاء جدول `beneficiary_profiles` لتخزين معلومات تفصيلية عن المستفيدين:

```php
Schema::create('beneficiary_profiles', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->string('id_number')->nullable();
    $table->date('date_of_birth')->nullable();
    $table->string('gender')->nullable();
    $table->string('marital_status')->nullable();
    $table->integer('family_members')->nullable();
    $table->string('income_level')->nullable();
    $table->string('housing_type')->nullable();
    $table->string('medical_condition')->nullable();
    $table->text('special_needs')->nullable();
    $table->string('employment_status')->nullable();
    $table->string('profession')->nullable();
    $table->string('education_level')->nullable();
    $table->text('documents')->nullable();
    $table->text('notes')->nullable();
    $table->string('verification_status')->default('pending');
    $table->string('verified_by')->nullable();
    $table->timestamp('verified_at')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

### 6. تحسين العلاقات بين الجداول

تم تعديل جداول الطلبات والكوبونات والمعاملات لتحسين العلاقات بينها:

```php
// تحسين جدول الكوبونات لإضافة نوع الكوبون
Schema::table('coupons', function (Blueprint $table) {
    $table->string('coupon_type')->nullable()->default('standard');
});

// تحسين جدول المعاملات
Schema::table('transactions', function (Blueprint $table) {
    $table->string('transaction_type')->nullable()->default('coupon_redemption');
});
```

### 7. تحسين أداء قاعدة البيانات

تمت إضافة فهارس على الحقول الأكثر استخداماً في الاستعلامات:

```php
// جدول المستخدمين
Schema::table('users', function (Blueprint $table) {
    $table->index('role');
    $table->index('status');
    $table->index('email');
});

// جدول المتاجر
Schema::table('stores', function (Blueprint $table) {
    $table->index('store_code');
    $table->index('status');
    $table->index('store_type');
    $table->unique('store_code');
});

// جدول المنظمات
Schema::table('organizations', function (Blueprint $table) {
    $table->index('organization_code');
    $table->index('status');
    $table->unique('organization_code');
});

// جدول الكوبونات
Schema::table('coupons', function (Blueprint $table) {
    $table->index('code');
    $table->index('is_redeemed');
    $table->index('expiration_date');
    $table->unique('code');
});
```

## نماذج البيانات المحدثة

تم تحديث نماذج البيانات لتعكس العلاقات الجديدة:

### 1. نموذج المستخدم (`User.php`)

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

### 2. نموذج المتجر (`Store.php`)

```php
class Store extends Model
{
    // العلاقة مع المستخدمين
    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user')
                    ->withPivot('role', 'is_primary')
                    ->withTimestamps();
    }
    
    // المستخدم الرئيسي للمتجر
    public function primaryUser()
    {
        return $this->belongsToMany(User::class, 'store_user')
                   ->withPivot('is_primary')
                   ->wherePivot('is_primary', true)
                   ->first();
    }
    
    // العلاقة مع المعاملات
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'store_id');
    }
}
```

### 3. نموذج المنظمة (`Organization.php`)

```php
class Organization extends Model
{
    // العلاقة مع المستخدمين
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot('role', 'is_primary')
                    ->withTimestamps();
    }
    
    // العلاقة مع المؤسسة الخيرية
    public function charity()
    {
        return $this->hasOne(CharityOrganization::class);
    }
    
    // العلاقة مع حملات التبرع
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
```

## الفوائد المحققة

1. **فصل واضح بين أنواع البيانات**: يمكن الآن إدارة المتاجر والمنظمات بشكل منفصل عن المستخدمين.
2. **إمكانية إسناد عدة مستخدمين**: يمكن ربط عدة مستخدمين بنفس المتجر أو المنظمة، مع تحديد أدوارهم.
3. **تقليل تكرار البيانات**: تم تقليل تكرار البيانات من خلال استخدام العلاقات بدلاً من تخزين نفس البيانات في جداول مختلفة.
4. **تحسين الأداء**: تم تحسين أداء الاستعلامات من خلال إضافة فهارس على الحقول الأكثر استخداماً.
5. **مرونة أكبر**: يمكن الآن إضافة المزيد من المعلومات التفصيلية لكل نوع من البيانات دون التأثير على الأنواع الأخرى.
6. **قابلية التوسع**: أصبح النظام أكثر قابلية للتوسع والتطوير في المستقبل.
7. **تحسين سلامة البيانات**: تم تعزيز سلامة البيانات من خلال استخدام العلاقات والمفاتيح الخارجية بشكل صحيح.

## الخطوات التالية

1. **تحديث واجهة المستخدم**: تطوير واجهات المستخدم لتعكس الهيكل الجديد للبيانات.
2. **تحسين نماذج البيانات**: إضافة المزيد من الدوال المساعدة والنطاقات (Scopes) لتسهيل الاستعلامات.
3. **تطوير نظام الإشعارات**: إضافة نظام إشعارات للمستخدمين والمتاجر والمنظمات.
4. **تطوير نظام التقارير**: تحسين نظام التقارير لتقديم إحصائيات أكثر تفصيلاً.
5. **إضافة اختبارات وحدة**: كتابة اختبارات وحدة للتأكد من عمل النظام بشكل صحيح. 