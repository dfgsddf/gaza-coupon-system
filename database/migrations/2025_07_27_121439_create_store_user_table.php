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
        Schema::create('store_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('store_id');
            $table->string('role')->default('owner')->comment('دور المستخدم في المتجر: owner, manager, employee');
            $table->boolean('is_primary')->default(false)->comment('هل المستخدم هو المسؤول الرئيسي للمتجر');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            
            $table->unique(['user_id', 'store_id']);
        });

        // ربط المستخدمين الحاليين من نوع متجر بالمتاجر الجديدة
        $storeUsers = DB::table('users')->where('role', 'store')->get();

        foreach ($storeUsers as $user) {
            $store = DB::table('stores')
                      ->where('email', $user->email)
                      ->first();
                      
            if ($store) {
                DB::table('store_user')->insert([
                    'user_id' => $user->id,
                    'store_id' => $store->id,
                    'role' => 'owner',
                    'is_primary' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_user');
    }
};
