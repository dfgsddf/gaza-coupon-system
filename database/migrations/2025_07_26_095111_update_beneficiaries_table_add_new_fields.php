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
        Schema::table('beneficiaries', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['full_name', 'id_number']);
            
            // Add new columns
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->string('first_name')->after('user_id');
            $table->string('last_name')->after('first_name');
            $table->string('email')->unique()->after('last_name');
            $table->string('emergency_contact')->after('phone');
            $table->json('documents')->nullable()->after('monthly_income');
            $table->enum('status', ['pending', 'active', 'suspended', 'approved'])->default('pending')->after('documents');
            $table->text('notes')->nullable()->after('status');
            
            // Add foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['user_id']);
            
            // Drop new columns
            $table->dropColumn([
                'user_id', 'first_name', 'last_name', 'email', 
                'emergency_contact', 'documents', 'status', 'notes'
            ]);
            
            // Restore old columns
            $table->string('full_name')->after('id');
            $table->string('id_number')->unique()->after('full_name');
        });
    }
};
