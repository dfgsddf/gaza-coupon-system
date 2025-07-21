<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('goal', 12, 2);
            $table->decimal('current_amount', 12, 2)->default(0);
            $table->string('status')->default('active'); // active, paused, completed, cancelled
            $table->foreignId('charity_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('donors_count')->default(0);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['charity_id', 'status']);
            $table->index(['status', 'is_featured']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
}; 