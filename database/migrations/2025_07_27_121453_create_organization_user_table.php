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
        // أولاً، نتأكد من أن جدول المنظمات يحتوي على الحقول اللازمة
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'organization_type')) {
                $table->string('organization_type')->nullable()->comment('نوع المنظمة: حكومية، غير ربحية، خيرية، الخ');
            }
            if (!Schema::hasColumn('organizations', 'logo')) {
                $table->string('logo')->nullable()->comment('شعار المنظمة');
            }
            if (!Schema::hasColumn('organizations', 'is_active')) {
                $table->boolean('is_active')->default(true)->comment('حالة المنظمة: نشطة أو غير نشطة');
            }
        });

        // إنشاء جدول العلاقة بين المستخدمين والمنظمات
        Schema::create('organization_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('role')->default('member')->comment('دور المستخدم في المنظمة: admin, member, volunteer');
            $table->boolean('is_primary')->default(false)->comment('هل المستخدم هو المسؤول الرئيسي للمنظمة');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            
            $table->unique(['user_id', 'organization_id']);
        });

        // ربط المستخدمين الحاليين من نوع charity بالمنظمات المناسبة
        $charityUsers = DB::table('users')->where('role', 'charity')->get();

        // إنشاء منظمات للمستخدمين من نوع charity إذا لم تكن موجودة
        foreach ($charityUsers as $user) {
            $organization = DB::table('organizations')
                              ->where('email', $user->email)
                              ->first();
                              
            if (!$organization) {
                // إنشاء منظمة جديدة
                $organizationId = DB::table('organizations')->insertGetId([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? null,
                    'address' => $user->address ?? null,
                    'status' => $user->status ?? 'active',
                    'organization_type' => 'charity',
                    'description' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $organizationId = $organization->id;
            }

            // ربط المستخدم بالمنظمة
            DB::table('organization_user')->insert([
                'user_id' => $user->id,
                'organization_id' => $organizationId,
                'role' => 'admin',
                'is_primary' => true,
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
        Schema::dropIfExists('organization_user');
    }
};
