# تقرير تحسينات تصميم قاعدة البيانات

## مقدمة

تم إجراء تحسينات شاملة على قاعدة بيانات نظام كوبونات غزة بهدف معالجة المشاكل التالية:

1. فصل أنواع البيانات المختلفة
2. تقليل التكرار في البيانات
3. تحسين العلاقات بين الجداول
4. تحسين الأداء من خلال إضافة الفهارس المناسبة
5. ضمان سلامة البيانات وتكاملها

تم تقسيم هذه التحسينات إلى ثلاث مراحل رئيسية:

## المرحلة الأولى: فصل الكيانات الرئيسية

### 1. فصل المستخدمين عن المتاجر

تم إنشاء جدول منفصل للمتاجر `stores` مع العلاقة بالمستخدمين عبر جدول وسيط `store_user`:

```php
Schema::create('stores', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->string('address')->nullable();
    $table->string('status')->default('active');
    $table->text('description')->nullable();
    $table->string('store_type')->nullable();
    $table->string('location_lat')->nullable();
    $table->string('location_lng')->nullable();
    $table->string('logo')->nullable();
    $table->timestamps();
});

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

### 2. فصل المستخدمين عن المنظمات

تم تحسين جدول المنظمات وإنشاء جدول وسيط `organization_user` للعلاقة بين المستخدمين والمنظمات:

```php
Schema::table('organizations', function (Blueprint $table) {
    // حقول إضافية للمنظمات
    if (!Schema::hasColumn('organizations', 'organization_type')) {
        $table->string('organization_type')->nullable();
    }
    // ... المزيد من الحقول
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

### 3. تحسين بيانات المستفيدين

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
    // ... المزيد من الحقول
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
```

### 4. إضافة معلومات تفصيلية للمؤسسات الخيرية

تم إنشاء جدول `charity_organizations` للمعلومات الخاصة بالمؤسسات الخيرية:

```php
Schema::create('charity_organizations', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('organization_id');
    $table->string('registration_number')->nullable();
    $table->string('license_number')->nullable();
    $table->date('license_expiry_date')->nullable();
    // ... المزيد من الحقول
    $table->timestamps();
    
    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
});
```

## المرحلة الثانية: تقليل التكرار وتحسين العلاقات

### 1. تحسين بيانات المتاجر

تم إضافة حقول خاصة بالمتجر كمؤسسة وإزالة التكرار مع بيانات المستخدمين:

```php
Schema::table('stores', function (Blueprint $table) {
    $table->string('store_code')->nullable();
    $table->boolean('has_physical_location')->default(true);
    $table->boolean('accepts_online_orders')->default(false);
    $table->string('tax_number')->nullable();
    $table->string('commercial_register')->nullable();
});
```

### 2. تحسين العلاقة بين الكوبونات والمعاملات

تم تحسين الترابط بين الكوبونات والمعاملات لضمان أن كل معاملة مرتبطة بكوبون واحد:

```php
// تأكد من أن لكل معاملة كوبون واحد فقط وليس العكس
$transactions = DB::table('transactions')
                ->whereNull('coupon_id')
                ->get();
            
// إصلاح المعاملات التي ليس لها كوبون
foreach ($transactions as $transaction) {
    // ربط المعاملة بالكوبون المناسب
    // ... كود الربط
}
```

### 3. توضيح العلاقات بين الكيانات

تم تحسين العلاقات بين الطلبات والمنظمات والكوبونات والمعاملات:

```php
// إضافة علاقات للطلبات
Schema::table('requests', function (Blueprint $table) {
    $table->unsignedBigInteger('organization_id')->nullable();
    $table->string('category')->nullable();
    $table->decimal('amount_requested', 10, 2)->nullable();
    
    $table->foreign('organization_id')->references('id')->on('organizations');
});

// إضافة علاقات للكوبونات
Schema::table('coupons', function (Blueprint $table) {
    $table->unsignedBigInteger('organization_id')->nullable();
    $table->string('coupon_type')->nullable()->default('standard');
    
    $table->foreign('organization_id')->references('id')->on('organizations');
});

// إضافة علاقات للمعاملات
Schema::table('transactions', function (Blueprint $table) {
    $table->string('transaction_type')->nullable()->default('coupon_redemption');
    $table->unsignedBigInteger('organization_id')->nullable();
    
    $table->foreign('organization_id')->references('id')->on('organizations');
});
```

## المرحلة الثالثة: تحسين الأداء وضمان سلامة البيانات

تم إضافة فهارس (indexes) على الحقول الرئيسية في كل جدول لتحسين أداء الاستعلامات:

### 1. فهارس للمستخدمين

```php
Schema::table('users', function (Blueprint $table) {
    $table->index('role');
    $table->index('status');
    $table->index('email');
    $table->index(['role', 'status']);
});
```

### 2. فهارس للمتاجر

```php
Schema::table('stores', function (Blueprint $table) {
    $table->index('store_code');
    $table->index('status');
    $table->index('store_type');
    $table->unique('store_code');
});
```

### 3. فهارس للمنظمات

```php
Schema::table('organizations', function (Blueprint $table) {
    $table->index('organization_code');
    $table->index('organization_type');
    $table->index('status');
    $table->unique('organization_code');
});
```

### 4. فهارس للكوبونات

```php
Schema::table('coupons', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('store_id');
    $table->index('organization_id');
    $table->index('code');
    $table->index('redeemed');
    $table->index(['expiry_date', 'redeemed']);
    $table->unique('code');
});
```

## النماذج (Models) المحدثة

تم تحديث النماذج لتعكس التغييرات في العلاقات بين الجداول:

### 1. نموذج المستخدم (`User.php`)

```php
// العلاقات الجديدة
public function stores()
{
    return $this->belongsToMany(Store::class, 'store_user')
                ->withPivot('role', 'is_primary')
                ->withTimestamps();
}

public function organizations()
{
    return $this->belongsToMany(Organization::class, 'organization_user')
                ->withPivot('role', 'is_primary')
                ->withTimestamps();
}

public function beneficiaryProfile()
{
    return $this->hasOne(BeneficiaryProfile::class);
}
```

### 2. نموذج المتجر (`Store.php`)

```php
class Store extends Model
{
    // العلاقات
    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user')
                    ->withPivot('role', 'is_primary')
                    ->withTimestamps();
    }
    
    public function primaryUser()
    {
        return $this->belongsToMany(User::class, 'store_user')
                   ->withPivot('is_primary')
                   ->wherePivot('is_primary', true)
                   ->first();
    }
    
    // المزيد من العلاقات والدوال المساعدة
}
```

### 3. نموذج المنظمة (`Organization.php`)

```php
class Organization extends Model
{
    // العلاقات
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
    
    // المزيد من العلاقات والدوال المساعدة
}
```

## مخطط قاعدة البيانات المحسنة

تم إنشاء مخطط تفصيلي لقاعدة البيانات المحسنة في ملف `DATABASE_STRUCTURE_DIAGRAM.md` يوضح الجداول والعلاقات بينها.

## الفوائد الرئيسية للتحسينات

1. **فصل واضح بين الكيانات**: يسمح بإدارة كل نوع من البيانات بشكل مستقل
2. **تقليل التكرار**: الحد من تكرار البيانات المشتركة بين الجداول
3. **علاقات أكثر دقة**: توضيح العلاقات بين المستخدمين والمتاجر والمنظمات والكوبونات
4. **تحسين الأداء**: من خلال إضافة الفهارس المناسبة على الحقول الأكثر استخداماً
5. **سلامة البيانات**: ضمان اتساق البيانات من خلال استخدام المفاتيح الخارجية والقيود الفريدة
6. **مرونة أكبر**: دعم أنواع مختلفة من العلاقات بين الكيانات
7. **قابلية التوسع**: سهولة إضافة ميزات وكيانات جديدة في المستقبل

## الخطوات التالية

1. **تنفيذ التحديثات**: تطبيق ملفات الترحيل على قاعدة البيانات
2. **اختبار العلاقات**: التأكد من عمل العلاقات الجديدة بشكل صحيح
3. **تحديث التطبيق**: تحديث الشيفرة المصدرية لاستخدام العلاقات الجديدة
4. **مراقبة الأداء**: متابعة أداء الاستعلامات بعد إضافة الفهارس

هذه التحسينات ستساهم في جعل نظام كوبونات غزة أكثر كفاءة وأكثر قابلية للصيانة والتوسع في المستقبل. 