<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charity_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charity_id')->constrained('users')->onDelete('cascade');
            $table->string('report_type'); // campaign_summary, donation_analysis, financial_report, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('data'); // Store report data as JSON
            $table->string('file_path')->nullable(); // For exported files
            $table->string('file_type')->nullable(); // pdf, excel, csv
            $table->date('report_date');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('generated'); // generated, exported, archived
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['charity_id', 'report_type']);
            $table->index(['report_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charity_reports');
    }
};
