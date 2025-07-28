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
        Schema::table('stores', function (Blueprint $table) {
            // فحص وإضافة store_code إذا لم يكن موجوداً
            if (!Schema::hasColumn('stores', 'store_code')) {
                $table->string('store_code')->unique()->nullable()->after('name');
            }
            
            // فحص وإضافة has_physical_location إذا لم يكن موجوداً
            if (!Schema::hasColumn('stores', 'has_physical_location')) {
                $table->boolean('has_physical_location')->default(true)->after('status');
            }
            
            // فحص وإضافة accepts_online_orders إذا لم يكن موجوداً
            if (!Schema::hasColumn('stores', 'accepts_online_orders')) {
                $table->boolean('accepts_online_orders')->default(false)->after('has_physical_location');
            }
            
            // فحص وإضافة tax_number إذا لم يكن موجوداً
            if (!Schema::hasColumn('stores', 'tax_number')) {
                $table->string('tax_number')->nullable()->after('accepts_online_orders');
            }
            
            // فحص وإضافة commercial_register إذا لم يكن موجوداً
            if (!Schema::hasColumn('stores', 'commercial_register')) {
                $table->string('commercial_register')->nullable()->after('tax_number');
            }
        });

        // تحديث store_code للمتاجر الموجودة
        $stores = \App\Models\Store::whereNull('store_code')->get();
        foreach ($stores as $index => $store) {
            $store->update([
                'store_code' => 'STR' . str_pad($store->id, 4, '0', STR_PAD_LEFT)
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $columnsToRemove = [];
            
            if (Schema::hasColumn('stores', 'store_code')) {
                $columnsToRemove[] = 'store_code';
            }
            if (Schema::hasColumn('stores', 'has_physical_location')) {
                $columnsToRemove[] = 'has_physical_location';
            }
            if (Schema::hasColumn('stores', 'accepts_online_orders')) {
                $columnsToRemove[] = 'accepts_online_orders';
            }
            if (Schema::hasColumn('stores', 'tax_number')) {
                $columnsToRemove[] = 'tax_number';
            }
            if (Schema::hasColumn('stores', 'commercial_register')) {
                $columnsToRemove[] = 'commercial_register';
            }
            
            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }
};
