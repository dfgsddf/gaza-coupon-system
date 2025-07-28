<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. إضافة organization_id إلى جدول requests
        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                if (!Schema::hasColumn('requests', 'organization_id')) {
                    $table->unsignedBigInteger('organization_id')->nullable()->after('user_id');
                    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('requests', 'amount_requested')) {
                    $table->decimal('amount_requested', 10, 2)->nullable()->after('description');
                }
                
                if (!Schema::hasColumn('requests', 'category')) {
                    $table->string('category')->nullable()->after('type');
                }
            });
        }

        // 2. إضافة organization_id و store_id إلى جدول coupons
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                if (!Schema::hasColumn('coupons', 'organization_id')) {
                    $table->unsignedBigInteger('organization_id')->nullable()->after('user_id');
                    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('coupons', 'store_id')) {
                    $table->unsignedBigInteger('store_id')->nullable()->after('organization_id');
                    $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('coupons', 'coupon_type')) {
                    $table->string('coupon_type')->nullable()->default('standard')->after('code');
                }
            });
        }

        // 3. إضافة organization_id إلى جدول transactions
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (!Schema::hasColumn('transactions', 'organization_id')) {
                    $table->unsignedBigInteger('organization_id')->nullable()->after('store_id');
                    $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('transactions', 'amount')) {
                    $table->decimal('amount', 10, 2)->nullable()->after('coupon_value');
                }
                
                if (!Schema::hasColumn('transactions', 'transaction_type')) {
                    $table->string('transaction_type')->nullable()->default('coupon_redemption')->after('amount');
                }
            });
        }

        // 4. إضافة فهارس للتحسين
        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                // التحقق من وجود الفهارس قبل إنشائها
                if (!Schema::hasIndex('requests', 'idx_requests_user_org')) {
                    $table->index(['user_id', 'organization_id'], 'idx_requests_user_org');
                }
                if (!Schema::hasIndex('requests', 'idx_requests_status_date')) {
                    $table->index(['status', 'created_at'], 'idx_requests_status_date');
                }
                if (!Schema::hasIndex('requests', 'idx_requests_type_status')) {
                    $table->index(['type', 'status'], 'idx_requests_type_status');
                }
            });
        }

        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                // التحقق من وجود الفهارس قبل إنشائها
                if (!Schema::hasIndex('coupons', 'idx_coupons_user_org')) {
                    $table->index(['user_id', 'organization_id'], 'idx_coupons_user_org');
                }
                if (!Schema::hasIndex('coupons', 'idx_coupons_redeemed_expiry')) {
                    $table->index(['redeemed', 'expiry_date'], 'idx_coupons_redeemed_expiry');
                }
                if (!Schema::hasIndex('coupons', 'idx_coupons_store_redeemed')) {
                    $table->index(['store_id', 'redeemed'], 'idx_coupons_store_redeemed');
                }
            });
        }

        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                // التحقق من وجود الفهارس قبل إنشائها
                if (!Schema::hasIndex('transactions', 'idx_transactions_store_beneficiary')) {
                    $table->index(['store_id', 'beneficiary_id'], 'idx_transactions_store_beneficiary');
                }
                if (!Schema::hasIndex('transactions', 'idx_transactions_status_date')) {
                    $table->index(['status', 'created_at'], 'idx_transactions_status_date');
                }
                if (!Schema::hasIndex('transactions', 'idx_transactions_org_status')) {
                    $table->index(['organization_id', 'status'], 'idx_transactions_org_status');
                }
            });
        }

        // 5. إضافة قيود للبيانات - تم إزالة check constraints لأنها غير مدعومة في Laravel
        // يمكن إضافة validation rules في Models بدلاً من ذلك
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إزالة القيود أولاً - تم إزالة dropCheck لأنها غير مدعومة في Laravel

        // إزالة الفهارس
        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                $table->dropIndex('idx_requests_user_org');
                $table->dropIndex('idx_requests_status_date');
                $table->dropIndex('idx_requests_type_status');
            });
        }

        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                $table->dropIndex('idx_coupons_user_org');
                $table->dropIndex('idx_coupons_redeemed_expiry');
                $table->dropIndex('idx_coupons_store_redeemed');
            });
        }

        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropIndex('idx_transactions_store_beneficiary');
                $table->dropIndex('idx_transactions_status_date');
                $table->dropIndex('idx_transactions_org_status');
            });
        }

        // إزالة الحقول المضافة
        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                if (Schema::hasColumn('requests', 'organization_id')) {
                    $table->dropForeign(['organization_id']);
                    $table->dropColumn('organization_id');
                }
                if (Schema::hasColumn('requests', 'amount_requested')) {
                    $table->dropColumn('amount_requested');
                }
                if (Schema::hasColumn('requests', 'category')) {
                    $table->dropColumn('category');
                }
            });
        }

        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                if (Schema::hasColumn('coupons', 'organization_id')) {
                    $table->dropForeign(['organization_id']);
                    $table->dropColumn('organization_id');
                }
                if (Schema::hasColumn('coupons', 'store_id')) {
                    $table->dropForeign(['store_id']);
                    $table->dropColumn('store_id');
                }
                if (Schema::hasColumn('coupons', 'coupon_type')) {
                    $table->dropColumn('coupon_type');
                }
            });
        }

        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (Schema::hasColumn('transactions', 'organization_id')) {
                    $table->dropForeign(['organization_id']);
                    $table->dropColumn('organization_id');
                }
                if (Schema::hasColumn('transactions', 'amount')) {
                    $table->dropColumn('amount');
                }
                if (Schema::hasColumn('transactions', 'transaction_type')) {
                    $table->dropColumn('transaction_type');
                }
            });
        }
    }
}; 