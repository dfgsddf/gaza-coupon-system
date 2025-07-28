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
        Schema::create('beneficiary_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('id_number')->nullable()->comment('رقم الهوية');
            $table->date('date_of_birth')->nullable()->comment('تاريخ الميلاد');
            $table->string('gender')->nullable()->comment('الجنس');
            $table->string('marital_status')->nullable()->comment('الحالة الاجتماعية');
            $table->integer('family_members')->nullable()->comment('عدد أفراد الأسرة');
            $table->string('income_level')->nullable()->comment('مستوى الدخل');
            $table->string('housing_type')->nullable()->comment('نوع السكن');
            $table->string('medical_condition')->nullable()->comment('الحالة الصحية');
            $table->text('special_needs')->nullable()->comment('احتياجات خاصة');
            $table->string('employment_status')->nullable()->comment('حالة التوظيف');
            $table->string('profession')->nullable()->comment('المهنة');
            $table->string('education_level')->nullable()->comment('المستوى التعليمي');
            $table->text('documents')->nullable()->comment('الوثائق المرفقة');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->string('verification_status')->default('pending')->comment('حالة التحقق: معلق، مؤكد، مرفوض');
            $table->string('verified_by')->nullable()->comment('تم التحقق بواسطة');
            $table->timestamp('verified_at')->nullable()->comment('تاريخ التحقق');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // نقل البيانات من جدول beneficiaries إلى جدول beneficiary_profiles
        if (Schema::hasTable('beneficiaries')) {
            $beneficiaries = DB::table('beneficiaries')->get();
            foreach ($beneficiaries as $beneficiary) {
                DB::table('beneficiary_profiles')->insert([
                    'user_id' => $beneficiary->user_id,
                    'id_number' => $beneficiary->id_number ?? null,
                    'verification_status' => $beneficiary->verification_status ?? 'pending',
                    'created_at' => $beneficiary->created_at ?? now(),
                    'updated_at' => $beneficiary->updated_at ?? now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiary_profiles');
    }
};
