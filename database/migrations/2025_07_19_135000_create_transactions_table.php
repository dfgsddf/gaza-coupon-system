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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('beneficiary_id');
            $table->unsignedBigInteger('coupon_id');
            $table->decimal('coupon_value', 10, 2);
            $table->string('beneficiary_name');
            $table->string('store_name');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('beneficiary_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
