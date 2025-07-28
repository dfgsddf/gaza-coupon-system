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
        // إنشاء الفهارس على الجداول الحيوية فقط لتحسين الأداء
        $this->createSafeIndexes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا نقوم بإزالة الفهارس
    }
    
    /**
     * إنشاء فهارس آمنة على الجداول والحقول الموجودة فقط
     */
    protected function createSafeIndexes()
    {
        // 1. جدول المستخدمين
        $this->createIndexesIfNotExists('users', [
            'role', 'status', 'email'
        ]);
        
        // 2. جدول المتاجر (إذا كان موجوداً)
        if (Schema::hasTable('stores')) {
            $this->createIndexesIfColumnsExist('stores', [
                'status', 'store_code', 'store_type'
            ]);
        }
        
        // 3. جدول المنظمات (إذا كان موجوداً)
        if (Schema::hasTable('organizations')) {
            $this->createIndexesIfColumnsExist('organizations', [
                'status', 'organization_code', 'organization_type'
            ]);
        }
        
        // 4. جدول الكوبونات
        if (Schema::hasTable('coupons')) {
            $this->createIndexesIfColumnsExist('coupons', [
                'code', 'is_redeemed', 'expiration_date', 'beneficiary_id'
            ]);
            
            // إنشاء فهرس فريد للكود إذا لم يكن موجوداً
            if (Schema::hasColumn('coupons', 'code') && !$this->hasUniqueIndex('coupons', 'code')) {
                try {
                    Schema::table('coupons', function (Blueprint $table) {
                        $table->unique('code');
                    });
                } catch (\Exception $e) {
                    // تجاهل الأخطاء في حالة وجود بيانات متكررة
                    DB::statement('ALTER TABLE coupons ADD INDEX coupons_code_index (code)');
                }
            }
        }
        
        // 5. جدول المعاملات
        if (Schema::hasTable('transactions')) {
            $this->createIndexesIfColumnsExist('transactions', [
                'status', 'coupon_id', 'beneficiary_id', 'store_id', 'created_at'
            ]);
        }
    }
    
    /**
     * إنشاء فهرس على عمود معين إذا كان العمود موجوداً والفهرس غير موجود
     */
    private function createIndexesIfColumnsExist($table, $columns)
    {
        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column) && !$this->hasIndex($table, $column)) {
                try {
                    Schema::table($table, function (Blueprint $table) use ($column) {
                        $table->index($column);
                    });
                } catch (\Exception $e) {
                    // تجاهل الأخطاء
                }
            }
        }
    }
    
    /**
     * إنشاء فهرس إذا لم يكن موجوداً
     */
    private function createIndexesIfNotExists($table, $columns)
    {
        foreach ($columns as $column) {
            if (!$this->hasIndex($table, $column)) {
                try {
                    Schema::table($table, function (Blueprint $table) use ($column) {
                        $table->index($column);
                    });
                } catch (\Exception $e) {
                    // تجاهل الأخطاء
                }
            }
        }
    }

    /**
     * التحقق من وجود فهرس على عمود معين
     */
    private function hasIndex($table, $column)
    {
        $indexName = $table . '_' . $column . '_index';
        try {
            $result = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$indexName}'");
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * التحقق من وجود فهرس فريد على عمود معين
     */
    private function hasUniqueIndex($table, $column)
    {
        $uniqueName = $table . '_' . $column . '_unique';
        try {
            $result = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$uniqueName}'");
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }
};
