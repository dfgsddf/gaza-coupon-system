<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('donor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->text('message')->nullable();
            $table->string('payment_method')->default('cash'); // cash, card, bank_transfer
            $table->string('payment_status')->default('completed'); // pending, completed, failed
            $table->string('transaction_id')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['campaign_id', 'payment_status']);
            $table->index(['donor_id', 'created_at']);
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_donations');
    }
};
