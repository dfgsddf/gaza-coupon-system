<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Add new fields if they don't exist
            if (!Schema::hasColumn('campaigns', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'current_amount')) {
                $table->decimal('current_amount', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('campaigns', 'charity_id')) {
                $table->foreignId('charity_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('campaigns', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'image_url')) {
                $table->string('image_url')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (!Schema::hasColumn('campaigns', 'donors_count')) {
                $table->integer('donors_count')->default(0);
            }
        });

        // Add indexes if they don't exist
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasIndex('campaigns', 'campaigns_charity_id_status_index')) {
                $table->index(['charity_id', 'status'], 'campaigns_charity_id_status_index');
            }
            if (!Schema::hasIndex('campaigns', 'campaigns_status_is_featured_index')) {
                $table->index(['status', 'is_featured'], 'campaigns_status_is_featured_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Remove indexes (will error if index does not exist)
            try {
                $table->dropIndex('campaigns_charity_id_status_index');
            } catch (\Exception $e) {}
            try {
                $table->dropIndex('campaigns_status_is_featured_index');
            } catch (\Exception $e) {}
            // Remove columns
            $table->dropColumn([
                'description',
                'current_amount',
                'charity_id',
                'start_date',
                'end_date',
                'image_url',
                'is_featured',
                'donors_count',
            ]);
        });
    }
};
