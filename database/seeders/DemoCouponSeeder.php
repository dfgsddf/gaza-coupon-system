<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;

class DemoCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get demo users
        $beneficiary = User::where('email', 'beneficiary@example.com')->first();
        $store = User::where('email', 'store@example.com')->first();

        if (!$beneficiary || !$store) {
            $this->command->error('Demo users not found. Please run UserSeeder first.');
            return;
        }

        // Create demo coupons
        $coupons = [
            [
                'user_id' => $beneficiary->id,
                'code' => 'COUPON_FOOD_001',
                'value' => 50.00,
                'description' => 'قسيمة طعام بقيمة 50 شيكل',
                'store_name' => $store->name,
                'expiry_date' => Carbon::now()->addDays(30),
                'redeemed' => false,
            ],
            [
                'user_id' => $beneficiary->id,
                'code' => 'COUPON_FOOD_002',
                'value' => 75.00,
                'description' => 'قسيمة طعام بقيمة 75 شيكل',
                'store_name' => $store->name,
                'expiry_date' => Carbon::now()->addDays(15),
                'redeemed' => false,
            ],
            [
                'user_id' => $beneficiary->id,
                'code' => 'COUPON_FOOD_003',
                'value' => 100.00,
                'description' => 'قسيمة طعام بقيمة 100 شيكل',
                'store_name' => $store->name,
                'expiry_date' => Carbon::now()->addDays(7),
                'redeemed' => false,
            ],
            [
                'user_id' => $beneficiary->id,
                'code' => 'COUPON_FOOD_004',
                'value' => 25.00,
                'description' => 'قسيمة طعام بقيمة 25 شيكل',
                'store_name' => $store->name,
                'expiry_date' => Carbon::now()->addDays(60),
                'redeemed' => false,
            ],
            [
                'user_id' => $beneficiary->id,
                'code' => 'COUPON_FOOD_005',
                'value' => 150.00,
                'description' => 'قسيمة طعام بقيمة 150 شيكل',
                'store_name' => $store->name,
                'expiry_date' => Carbon::now()->addDays(45),
                'redeemed' => false,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }

        // Create some redeemed coupons for testing
        $redeemedCoupons = [
            [
                'user_id' => $beneficiary->id,
                'code' => 'COUPON_REDEEMED_001',
                'value' => 30.00,
                'description' => 'قسيمة مستردة بقيمة 30 شيكل',
                'store_name' => $store->name,
                'expiry_date' => Carbon::now()->addDays(10),
                'redeemed' => true,
                'redeemed_at' => Carbon::now()->subDays(2),
            ],
            [
                'user_id' => $beneficiary->id,
                'code' => 'COUPON_REDEEMED_002',
                'value' => 45.00,
                'description' => 'قسيمة مستردة بقيمة 45 شيكل',
                'store_name' => $store->name,
                'expiry_date' => Carbon::now()->addDays(5),
                'redeemed' => true,
                'redeemed_at' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($redeemedCoupons as $couponData) {
            Coupon::create($couponData);
        }

        $this->command->info('Demo coupons created successfully!');
        $this->command->info('Available coupon codes for testing:');
        $this->command->info('- COUPON_FOOD_001 (50.00)');
        $this->command->info('- COUPON_FOOD_002 (75.00)');
        $this->command->info('- COUPON_FOOD_003 (100.00)');
        $this->command->info('- COUPON_FOOD_004 (25.00)');
        $this->command->info('- COUPON_FOOD_005 (150.00)');
    }
}
