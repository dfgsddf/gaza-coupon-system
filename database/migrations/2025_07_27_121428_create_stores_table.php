<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added this import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default('active');
            $table->text('description')->nullable();
            $table->string('store_type')->nullable()->comment('نوع المتجر: مواد غذائية، ملابس، الخ');
            $table->string('location_lat')->nullable()->comment('خط العرض للموقع');
            $table->string('location_lng')->nullable()->comment('خط الطول للموقع');
            $table->string('logo')->nullable()->comment('شعار المتجر');
            $table->timestamps();
        });

        // نقل البيانات من جدول المستخدمين إلى جدول المتاجر الجديد
        DB::statement("
            INSERT INTO stores (name, email, phone, address, status, description, created_at, updated_at)
            SELECT name, email, phone, address, status, store_description, created_at, updated_at
            FROM users
            WHERE role = 'store'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
