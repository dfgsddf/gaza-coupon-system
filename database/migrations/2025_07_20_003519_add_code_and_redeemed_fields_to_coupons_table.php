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
        Schema::table('coupons', function (Blueprint $table) {
            // Only add these fields if they don't exist
            if (!Schema::hasColumn('coupons', 'redeemed')) {
                $table->boolean('redeemed')->default(false)->after('expiry_date');
            }
            if (!Schema::hasColumn('coupons', 'redeemed_at')) {
                $table->timestamp('redeemed_at')->nullable()->after('redeemed');
            }
        });

        // Update existing coupons with unique codes if code column exists but is empty
        if (Schema::hasColumn('coupons', 'code')) {
            $coupons = DB::table('coupons')->whereNull('code')->orWhere('code', '')->get();
            foreach ($coupons as $coupon) {
                $uniqueCode = 'COUPON_' . strtoupper(uniqid()) . '_' . $coupon->id;
                DB::table('coupons')->where('id', $coupon->id)->update(['code' => $uniqueCode]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['redeemed', 'redeemed_at']);
        });
    }
};
