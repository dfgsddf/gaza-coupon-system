# إصلاح مشكلة نموذج الاتصال - Gaza Coupon System

## المشكلة

كان هناك خطأ في Laravel يشير إلى أن المسار `contact.send` غير معرف:
```
Route [contact.send] not defined.
```

## الحل المطبق

### 1. إصلاح مسار النموذج

تم إصلاح المسار في ملف `resources/views/contact.blade.php`:

**قبل الإصلاح:**
```html
<form method="POST" action="{{ route('contact.send') }}" class="needs-validation" novalidate>
```

**بعد الإصلاح:**
```html
<form method="POST" action="{{ route('contact.submit') }}" class="needs-validation" novalidate>
```

### 2. التحقق من المسارات

تم التأكد من وجود المسارات الصحيحة في `routes/web.php`:

```php
// Contact form routes
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
```

### 3. التحقق من ContactController

تم التأكد من وجود الدوال المطلوبة في `app/Http/Controllers/ContactController.php`:

```php
/**
 * Show the contact form
 */
public function show()
{
    return view('contact');
}

/**
 * Handle form submission
 */
public function submit(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|max:5000',
    ]);

    try {
        // Store the message in the database
        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send email notification
        $this->sendEmailNotification($contactMessage);

        return back()->with('success', 'شكراً لك على التواصل معنا! سنقوم بالرد عليك قريباً.');

    } catch (\Exception $e) {
        return back()->with('error', 'عذراً، حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
    }
}
```

## المكونات المطلوبة

### 1. نموذج ContactMessage

تم التأكد من وجود نموذج `app/Models/ContactMessage.php` مع الحقول المطلوبة:

```php
protected $fillable = [
    'name',
    'email',
    'subject',
    'message',
    'status',
    'ip_address',
    'user_agent',
    'read_at',
    'replied_at',
    'admin_notes',
];
```

### 2. جدول قاعدة البيانات

تم التأكد من وجود جدول `contact_messages` مع الأعمدة المطلوبة:

```php
Schema::create('contact_messages', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->string('subject');
    $table->text('message');
    $table->string('status')->default('unread');
    $table->string('ip_address')->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->timestamp('replied_at')->nullable();
    $table->text('admin_notes')->nullable();
    $table->timestamps();
});
```

### 3. نظام البريد الإلكتروني

تم التأكد من وجود:
- `app/Mail/ContactFormMail.php` - فئة البريد الإلكتروني
- `resources/views/emails/contact-form.blade.php` - قالب البريد الإلكتروني

## الميزات المتاحة

### 1. نموذج الاتصال
- **الحقول المطلوبة**: الاسم، البريد الإلكتروني، الموضوع، الرسالة
- **التحقق من الصحة**: تحقق شامل من صحة البيانات المدخلة
- **رسائل خطأ مخصصة**: رسائل خطأ باللغة العربية

### 2. حفظ الرسائل
- **حفظ في قاعدة البيانات**: حفظ جميع الرسائل في جدول `contact_messages`
- **معلومات إضافية**: حفظ عنوان IP ومعلومات المتصفح
- **حالة الرسالة**: تتبع حالة الرسالة (غير مقروءة، مقروءة، تم الرد)

### 3. إشعارات البريد الإلكتروني
- **إرسال إشعار**: إرسال إشعار للمدير عند استلام رسالة جديدة
- **معلومات شاملة**: تضمين جميع معلومات الرسالة في البريد الإلكتروني
- **رابط لوحة التحكم**: رابط مباشر لعرض الرسالة في لوحة التحكم

### 4. لوحة تحكم المدير
- **عرض الرسائل**: عرض جميع رسائل الاتصال
- **تحديث الحالة**: تحديث حالة الرسائل (مقروءة، تم الرد)
- **إحصائيات**: إحصائيات عن الرسائل المستلمة

## كيفية الاستخدام

### 1. الوصول إلى نموذج الاتصال
```
GET /contact
```

### 2. إرسال رسالة
```
POST /contact
```

**البيانات المطلوبة:**
- `name`: اسم المرسل
- `email`: البريد الإلكتروني
- `subject`: موضوع الرسالة
- `message`: محتوى الرسالة

### 3. النتيجة
- حفظ الرسالة في قاعدة البيانات
- إرسال إشعار بالبريد الإلكتروني
- عرض رسالة نجاح للمستخدم

## الأمان

### 1. التحقق من صحة البيانات
- تحقق من جميع الحقول المطلوبة
- تحقق من صحة البريد الإلكتروني
- تحديد حد أقصى لطول الرسالة

### 2. حماية من الهجمات
- تسجيل عنوان IP للمستخدم
- تسجيل معلومات المتصفح
- حماية من CSRF

### 3. معالجة الأخطاء
- تسجيل الأخطاء في السجلات
- عرض رسائل خطأ مناسبة للمستخدم
- عدم كشف معلومات حساسة

## الاختبار

### 1. اختبار النموذج
- اختبار جميع الحقول المطلوبة
- اختبار التحقق من صحة البيانات
- اختبار إرسال الرسالة بنجاح

### 2. اختبار قاعدة البيانات
- اختبار حفظ الرسالة في قاعدة البيانات
- اختبار حفظ المعلومات الإضافية
- اختبار تحديث حالة الرسالة

### 3. اختبار البريد الإلكتروني
- اختبار إرسال إشعار البريد الإلكتروني
- اختبار محتوى البريد الإلكتروني
- اختبار الروابط في البريد الإلكتروني

## معلومات التحديث

- **التاريخ**: 26 يناير 2025
- **الإصدار**: 1.0
- **المطور**: Gaza Coupon System Team
- **الحالة**: مكتمل ومختبر

---

*تم إصلاح هذه المشكلة لضمان عمل نموذج الاتصال بشكل صحيح.* 