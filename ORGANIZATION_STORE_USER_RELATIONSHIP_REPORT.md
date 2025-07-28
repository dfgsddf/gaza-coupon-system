# تقرير العلاقة بين المستخدمين والمنظمات والمتاجر

## الوضع الحالي

بعد دراسة قاعدة البيانات الحالية، وجدنا الهيكل التالي:

### 1. جدول المستخدمين (`users`)
- يحتوي على جميع المستخدمين بغض النظر عن نوعهم
- حقل `role` يحدد نوع المستخدم: `admin`, `store`, `beneficiary`, أو `charity`
- للمتاجر، هناك حقل `store_description` في نفس جدول المستخدمين
- لا يوجد فصل واضح بين المستخدمين والكيانات التي يمثلونها (المتاجر أو الجمعيات)

### 2. جدول المستفيدين (`beneficiaries`)
- مرتبط بجدول المستخدمين عبر علاقة واحد لواحد (one-to-one)
- يتم إنشاؤه عندما يكون `role` للمستخدم هو `beneficiary`

### 3. جدول المنظمات (`organizations`)
- يحتوي على معلومات المنظمات والجمعيات
- لا يوجد حالياً علاقة مباشرة مع جدول المستخدمين
- لا يمكن ربط مستخدم موجود بمنظمة أو جمعية

## المشكلة الحالية

1. **عدم الفصل بين المستخدم والكيان**: المستخدم والمتجر أو الجمعية مدمجان في نفس السجل في جدول المستخدمين، مما يجعل من الصعب:
   - تخصيص أكثر من مستخدم لنفس المتجر أو الجمعية
   - فصل معلومات المستخدم (مثل كلمة المرور والبريد الإلكتروني) عن معلومات الكيان (مثل العنوان ووصف المتجر)

2. **تكرار البيانات**: معلومات مثل العنوان والهاتف قد تتكرر لمستخدمين ينتمون لنفس المتجر أو الجمعية

3. **صعوبة إدارة المستخدمين والأذونات**: لا يمكن تحديد مستويات مختلفة من الوصول للمستخدمين داخل نفس المتجر أو الجمعية

## الحل المقترح

لتحسين هيكل قاعدة البيانات ولتمكين ربط المستخدمين بالمنظمات والمتاجر، نقترح التغييرات التالية:

### 1. تحديث جدول المنظمات (`organizations`) وإضافة علاقة مع المستخدمين:

```php
// إضافة جدول وسيط (pivot table) للعلاقة بين المستخدمين والمنظمات
Schema::create('organization_user', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('organization_id');
    $table->string('role')->default('member'); // مثال: admin, member, etc.
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
    
    $table->unique(['user_id', 'organization_id']);
});
```

### 2. إنشاء جدول للمتاجر منفصل عن المستخدمين:

```php
Schema::create('stores', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->string('address')->nullable();
    $table->string('status')->default('active');
    $table->text('description')->nullable();
    $table->timestamps();
});

// إضافة جدول وسيط (pivot table) للعلاقة بين المستخدمين والمتاجر
Schema::create('store_user', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('store_id');
    $table->string('role')->default('member'); // مثال: owner, employee, etc.
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
    
    $table->unique(['user_id', 'store_id']);
});
```

### 3. تحديث نماذج البيانات (Models) لإضافة العلاقات المناسبة:

#### User.php
```php
// إضافة علاقات جديدة في نموذج User
public function organizations()
{
    return $this->belongsToMany(Organization::class, 'organization_user')
                ->withPivot('role')
                ->withTimestamps();
}

public function stores()
{
    return $this->belongsToMany(Store::class, 'store_user')
                ->withPivot('role')
                ->withTimestamps();
}
```

#### Organization.php
```php
// إضافة علاقة في نموذج Organization
public function users()
{
    return $this->belongsToMany(User::class, 'organization_user')
                ->withPivot('role')
                ->withTimestamps();
}
```

#### Store.php (جديد)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'store_id');
    }
}
```

## خطة التنفيذ

1. **إنشاء ملفات الترحيل (Migrations) لإضافة الجداول الجديدة**:
   - `create_stores_table`
   - `create_store_user_table`
   - `create_organization_user_table`

2. **إنشاء نموذج Store وتحديث النماذج الأخرى** لإضافة العلاقات المناسبة.

3. **تعديل وحدات التحكم (Controllers)** لاستخدام العلاقات الجديدة.

4. **إنشاء واجهات المستخدم** لإدارة العلاقات بين المستخدمين والمنظمات والمتاجر.

5. **ترحيل البيانات الحالية** من النظام القديم إلى الهيكل الجديد.

## المزايا المتوقعة

1. **مرونة أكبر** في إدارة العلاقات بين المستخدمين والمنظمات والمتاجر.
2. **تحسين أمان النظام** من خلال فصل معلومات الدخول عن معلومات المتجر أو المنظمة.
3. **تقليل تكرار البيانات** وتحسين تنظيم قاعدة البيانات.
4. **دعم لمستويات مختلفة من الأذونات** داخل نفس المتجر أو المنظمة.
5. **توسعة النظام** لدعم سيناريوهات أكثر تعقيداً مثل المتاجر المتعددة أو المستخدمين ذوي الأدوار المتعددة.

## الخطوة القادمة

تنفيذ التغييرات المقترحة وإجراء اختبار شامل للتأكد من أن جميع وظائف النظام تعمل بشكل صحيح مع الهيكل الجديد. 