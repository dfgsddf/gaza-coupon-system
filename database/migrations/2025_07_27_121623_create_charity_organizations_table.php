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
        Schema::create('charity_organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('registration_number')->nullable()->comment('رقم تسجيل المؤسسة الخيرية');
            $table->string('license_number')->nullable()->comment('رقم الترخيص');
            $table->date('license_expiry_date')->nullable()->comment('تاريخ انتهاء الترخيص');
            $table->text('mission_statement')->nullable()->comment('رسالة المؤسسة');
            $table->text('vision_statement')->nullable()->comment('رؤية المؤسسة');
            $table->text('services')->nullable()->comment('الخدمات التي تقدمها المؤسسة');
            $table->string('contact_person')->nullable()->comment('الشخص المسؤول للاتصال');
            $table->string('website')->nullable()->comment('الموقع الإلكتروني');
            $table->string('bank_account')->nullable()->comment('رقم الحساب البنكي');
            $table->string('bank_name')->nullable()->comment('اسم البنك');
            $table->timestamps();
            
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });

        // ربط المنظمات من نوع charity بجدول المؤسسات الخيرية
        $charityOrgs = DB::table('organizations')
                        ->where('organization_type', 'charity')
                        ->get();

        foreach ($charityOrgs as $org) {
            DB::table('charity_organizations')->insert([
                'organization_id' => $org->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charity_organizations');
    }
};
