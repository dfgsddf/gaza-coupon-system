<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. تحسين جدول المتاجر: إزالة الحقول التي يمكن استرجاعها من المستخدم الرئيسي
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                // إضافة معرّف خاص بالمتجر إذا لم يكن موجوداً
                if (!Schema::hasColumn('stores', 'store_code')) {
                    $table->string('store_code')->nullable()->after('name')->comment('رمز المتجر الفريد');
                }
                
                // الحقول التي تنتمي للمتجر كمؤسسة فقط وليست مكررة مع المستخدم
                if (!Schema::hasColumn('stores', 'has_physical_location')) {
                    $table->boolean('has_physical_location')->default(true)->comment('هل المتجر له موقع فعلي');
                }
                
                if (!Schema::hasColumn('stores', 'accepts_online_orders')) {
                    $table->boolean('accepts_online_orders')->default(false)->comment('هل يقبل طلبات أونلاين');
                }
                
                if (!Schema::hasColumn('stores', 'tax_number')) {
                    $table->string('tax_number')->nullable()->comment('الرقم الضريبي للمتجر');
                }
                
                if (!Schema::hasColumn('stores', 'commercial_register')) {
                    $table->string('commercial_register')->nullable()->comment('رقم السجل التجاري');
                }
            });
        }

        // 2. تحسين العلاقة بين الكوبونات والمعاملات
        if (Schema::hasTable('coupons') && Schema::hasTable('transactions')) {
            // تأكد من أن لكل معاملة كوبون واحد فقط وليس العكس
            $transactions = DB::table('transactions')
                            ->whereNull('coupon_id')
                            ->get();
            
            // إصلاح المعاملات التي ليس لها كوبون
            foreach ($transactions as $transaction) {
                // ابحث عن كوبون مرتبط بنفس المستفيد والمتجر إن وجد
                $coupon = DB::table('coupons')
                          ->where('user_id', $transaction->beneficiary_id)
                          ->where('store_id', $transaction->store_id)
                          ->whereNull('redeemed_at')
                          ->first();
                          
                if ($coupon) {
                    // ربط المعاملة بالكوبون
                    DB::table('transactions')
                      ->where('id', $transaction->id)
                      ->update([
                          'coupon_id' => $coupon->id,
                          'updated_at' => now()
                      ]);
                      
                    // تحديث حالة الكوبون إلى مستخدم
                    DB::table('coupons')
                      ->where('id', $coupon->id)
                      ->update([
                          'redeemed' => true,
                          'redeemed_at' => $transaction->created_at,
                          'updated_at' => now()
                      ]);
                }
            }
        }

        // 3. تحسين جدول المنظمات: إزالة التكرار مع المستخدمين
        if (Schema::hasTable('organizations')) {
            Schema::table('organizations', function (Blueprint $table) {
                // إضافة حقول جديدة للمنظمات إذا لم تكن موجودة
                if (!Schema::hasColumn('organizations', 'organization_code')) {
                    $table->string('organization_code')->nullable()->after('name')->comment('رمز المنظمة الفريد');
                }
                
                if (!Schema::hasColumn('organizations', 'organization_type')) {
                    $table->string('organization_type')->nullable()->after('name')->comment('نوع المنظمة');
                }
                
                if (!Schema::hasColumn('organizations', 'status')) {
                    $table->string('status')->default('active')->after('name')->comment('حالة المنظمة');
                }
                
                if (!Schema::hasColumn('organizations', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('status')->comment('نشطة أم لا');
                }
            });
        }

        // 4. تعديل جدول طلبات المستفيدين لتحسين العلاقات
        if (Schema::hasTable('requests')) {
            // الجدول يحتوي بالفعل على organization_id و category و amount_requested
            // لا نحتاج لإضافة أي حقول جديدة
        }

        // 5. تحديث جدول الكوبونات لتحسين العلاقات
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                // جدول الكوبونات يحتوي بالفعل على organization_id، نضيف فقط نوع الكوبون
                if (!Schema::hasColumn('coupons', 'coupon_type')) {
                    $table->string('coupon_type')->nullable()->default('standard')->after('code')->comment('نوع الكوبون: قياسي، خاص، هدية');
                }
            });
        }

        // 6. تحديث جدول المعاملات لتوضيح العلاقات
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                // إضافة حقل لتصنيف المعاملة
                if (!Schema::hasColumn('transactions', 'transaction_type')) {
                    $table->string('transaction_type')->nullable()->default('coupon_redemption')->after('coupon_value')
                        ->comment('نوع المعاملة: استبدال كوبون، تبرع، إلخ');
                }
                
                // إضافة حقل للمنظمة المرتبطة بالمعاملة
                if (!Schema::hasColumn('transactions', 'organization_id')) {
                    $table->unsignedBigInteger('organization_id')->nullable()->after('store_id')->comment('المنظمة المرتبطة بالمعاملة');
                    $table->foreign('organization_id')->references('id')->on('organizations');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة الجداول للحالة السابقة

        // 1. إعادة جدول المتاجر
        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn([
                    'store_code',
                    'has_physical_location',
                    'accepts_online_orders',
                    'tax_number',
                    'commercial_register'
                ]);
            });
        }

        // 3. إعادة جدول المنظمات
        if (Schema::hasTable('organizations')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('organization_code');
                $table->dropColumn('organization_type');
                $table->dropColumn('status');
                $table->dropColumn('is_active');
            });
        }

        // 4. إعادة جدول طلبات المستفيدين
        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                if (Schema::hasColumn('requests', 'organization_id')) {
                    $table->dropForeign(['organization_id']);
                    $table->dropColumn('organization_id');
                }
                if (Schema::hasColumn('requests', 'category')) {
                    $table->dropColumn('category');
                }
                if (Schema::hasColumn('requests', 'amount_requested')) {
                    $table->dropColumn('amount_requested');
                }
            });
        }

        // 5. إعادة جدول الكوبونات
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                if (Schema::hasColumn('coupons', 'organization_id')) {
                    $table->dropForeign(['organization_id']);
                    $table->dropColumn('organization_id');
                }
                if (Schema::hasColumn('coupons', 'coupon_type')) {
                    $table->dropColumn('coupon_type');
                }
            });
        }

        // 6. إعادة جدول المعاملات
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (Schema::hasColumn('transactions', 'transaction_type')) {
                    $table->dropColumn('transaction_type');
                }
                if (Schema::hasColumn('transactions', 'organization_id')) {
                    $table->dropForeign(['organization_id']);
                    $table->dropColumn('organization_id');
                }
            });
        }
    }
};
