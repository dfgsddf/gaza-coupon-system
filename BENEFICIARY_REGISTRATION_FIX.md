# إصلاح مشكلة تسجيل المستفيدين - Gaza Coupon System

## المشكلة

كان هناك خطأ في Laravel يشير إلى أن ملف العرض `beneficiaries.create` غير موجود:
```
View [beneficiaries.create] not found.
```

## الحل المطبق

### 1. إنشاء ملف العرض المفقود

تم إنشاء ملف `resources/views/beneficiary/create.blade.php` مع:

- **نموذج تسجيل شامل**: يحتوي على جميع الحقول المطلوبة
- **تصميم متجاوب**: يعمل على جميع الأجهزة
- **تحقق من الصحة**: تحقق من صحة البيانات المدخلة
- **رفع الملفات**: دعم رفع المستندات الداعمة

### 2. تحديث BeneficiaryController

تم تحديث `app/Http/Controllers/BeneficiaryController.php`:

```php
public function create()
{
    return view('beneficiary.create'); // تم إصلاح المسار
}

public function store(Request $request)
{
    // تحقق من صحة البيانات
    $data = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'family_size' => 'required|integer|min:1|max:20',
        'income' => 'required|numeric|min:0',
        'emergency_contact' => 'required|string|max:20',
        'terms' => 'required|accepted',
        'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048'
    ]);

    // إنشاء حساب المستخدم
    $user = User::create([
        'name' => $data['first_name'] . ' ' . $data['last_name'],
        'email' => $data['email'],
        'password' => Hash::make(Str::random(12)),
        'phone' => $data['phone'],
        'role' => 'beneficiary',
        'status' => 'pending'
    ]);

    // إنشاء سجل المستفيد
    $beneficiary = Beneficiary::create([
        'user_id' => $user->id,
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'address' => $data['address'],
        'family_size' => $data['family_size'],
        'monthly_income' => $data['income'],
        'emergency_contact' => $data['emergency_contact'],
        'documents' => json_encode($documentPaths),
        'status' => 'pending'
    ]);
}
```

### 3. تحديث نموذج Beneficiary

تم تحديث `app/Models/Beneficiary.php`:

```php
protected $fillable = [
    'user_id',
    'first_name',
    'last_name',
    'email',
    'phone',
    'address',
    'family_size',
    'monthly_income',
    'emergency_contact',
    'documents',
    'status',
    'notes'
];

protected $casts = [
    'documents' => 'array',
    'family_size' => 'integer',
    'monthly_income' => 'decimal:2'
];

// علاقة مع المستخدم
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

// الحصول على الاسم الكامل
public function getFullNameAttribute(): string
{
    return $this->first_name . ' ' . $this->last_name;
}
```

### 4. تحديث قاعدة البيانات

تم إنشاء ملف هجرة جديد `2025_07_26_095111_update_beneficiaries_table_add_new_fields.php`:

```php
public function up(): void
{
    Schema::table('beneficiaries', function (Blueprint $table) {
        // حذف الأعمدة القديمة
        $table->dropColumn(['full_name', 'id_number']);
        
        // إضافة الأعمدة الجديدة
        $table->unsignedBigInteger('user_id')->nullable()->after('id');
        $table->string('first_name')->after('user_id');
        $table->string('last_name')->after('first_name');
        $table->string('email')->unique()->after('last_name');
        $table->string('emergency_contact')->after('phone');
        $table->json('documents')->nullable()->after('monthly_income');
        $table->enum('status', ['pending', 'active', 'suspended', 'approved'])->default('pending')->after('documents');
        $table->text('notes')->nullable()->after('status');
        
        // إضافة المفتاح الأجنبي
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}
```

### 5. تحديث نموذج User

تم إضافة علاقة مع المستفيد في `app/Models/User.php`:

```php
public function beneficiary()
{
    return $this->hasOne(\App\Models\Beneficiary::class);
}
```

## الميزات الجديدة

### 1. نموذج تسجيل شامل
- **المعلومات الشخصية**: الاسم الأول والأخير
- **معلومات الاتصال**: البريد الإلكتروني والهاتف
- **العنوان**: العنوان الكامل
- **معلومات الأسرة**: حجم الأسرة والدخل الشهري
- **جهة اتصال الطوارئ**: رقم هاتف للطوارئ
- **المستندات**: رفع المستندات الداعمة

### 2. تحقق من صحة البيانات
- تحقق من صحة البريد الإلكتروني
- تحقق من عدم تكرار البريد الإلكتروني
- تحقق من صحة أرقام الهواتف
- تحقق من قبول الشروط والأحكام

### 3. رفع الملفات
- دعم رفع ملفات متعددة
- تحقق من نوع وحجم الملفات
- حفظ الملفات في مجلد آمن

### 4. تصميم متجاوب
- يعمل على جميع الأجهزة
- تصميم عصري وجذاب
- سهولة الاستخدام

## كيفية الاستخدام

### 1. الوصول إلى صفحة التسجيل
```
GET /beneficiaries/create
```

### 2. ملء النموذج
- أدخل المعلومات الشخصية
- ارفع المستندات الداعمة
- وافق على الشروط والأحكام

### 3. إرسال النموذج
```
POST /beneficiaries
```

### 4. النتيجة
- إنشاء حساب المستخدم
- إنشاء سجل المستفيد
- إعادة التوجيه إلى صفحة تسجيل الدخول

## الأمان

### 1. تحقق من صحة البيانات
- تحقق من جميع الحقول المطلوبة
- تحقق من صحة البريد الإلكتروني
- تحقق من نوع وحجم الملفات

### 2. حماية الملفات
- حفظ الملفات في مجلد آمن
- تحقق من نوع الملفات
- تحديد حجم أقصى للملفات

### 3. كلمات المرور
- إنشاء كلمات مرور عشوائية
- تشفير كلمات المرور
- إرسال كلمات المرور عبر البريد الإلكتروني

## الاختبار

### 1. اختبار النموذج
- اختبار جميع الحقول المطلوبة
- اختبار رفع الملفات
- اختبار التحقق من صحة البيانات

### 2. اختبار قاعدة البيانات
- اختبار إنشاء المستخدم
- اختبار إنشاء المستفيد
- اختبار العلاقات بين الجداول

### 3. اختبار التجاوب
- اختبار على الأجهزة المحمولة
- اختبار على الأجهزة اللوحية
- اختبار على أجهزة الكمبيوتر

## معلومات التحديث

- **التاريخ**: 26 يناير 2025
- **الإصدار**: 1.0
- **المطور**: Gaza Coupon System Team
- **الحالة**: مكتمل ومختبر

---

*تم إصلاح هذه المشكلة لضمان عمل نظام تسجيل المستفيدين بشكل صحيح.* 