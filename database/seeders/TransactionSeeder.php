<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Get the store user
        $store = User::where('email', 'store@example.com')->first();
        
        // Get the beneficiary user
        $beneficiary = User::where('email', 'beneficiary@example.com')->first();
        
        if (!$store || !$beneficiary) {
            return;
        }

        // Get some coupons
        $coupons = Coupon::where('user_id', $beneficiary->id)->take(5)->get();

        if ($coupons->isEmpty()) {
            return;
        }

        // Create demo transactions
        $transactions = [
            [
                'store_id' => $store->id,
                'beneficiary_id' => $beneficiary->id,
                'coupon_id' => $coupons->first()->id,
                'coupon_value' => 50.00,
                'beneficiary_name' => $beneficiary->name,
                'store_name' => 'Gs Store',
                'status' => 'completed',
                'redeemed_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'store_id' => $store->id,
                'beneficiary_id' => $beneficiary->id,
                'coupon_id' => $coupons->skip(1)->first()->id ?? $coupons->first()->id,
                'coupon_value' => 22.00,
                'beneficiary_name' => $beneficiary->name,
                'store_name' => 'Pharmacy',
                'status' => 'completed',
                'redeemed_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'store_id' => $store->id,
                'beneficiary_id' => $beneficiary->id,
                'coupon_id' => $coupons->skip(2)->first()->id ?? $coupons->first()->id,
                'coupon_value' => 30.00,
                'beneficiary_name' => $beneficiary->name,
                'store_name' => 'Wael Store',
                'status' => 'completed',
                'redeemed_at' => Carbon::now()->subHours(6),
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'store_id' => $store->id,
                'beneficiary_id' => $beneficiary->id,
                'coupon_id' => $coupons->skip(3)->first()->id ?? $coupons->first()->id,
                'coupon_value' => 45.00,
                'beneficiary_name' => $beneficiary->name,
                'store_name' => 'Ali Store',
                'status' => 'completed',
                'redeemed_at' => Carbon::now()->subHours(2),
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'store_id' => $store->id,
                'beneficiary_id' => $beneficiary->id,
                'coupon_id' => $coupons->last()->id,
                'coupon_value' => 25.00,
                'beneficiary_name' => $beneficiary->name,
                'store_name' => 'Gs Store',
                'status' => 'completed',
                'redeemed_at' => Carbon::now()->subMinutes(30),
                'created_at' => Carbon::now()->subMinutes(30),
            ],
        ];

        foreach ($transactions as $transactionData) {
            Transaction::updateOrCreate(
                [
                    'store_id' => $transactionData['store_id'],
                    'beneficiary_id' => $transactionData['beneficiary_id'],
                    'coupon_id' => $transactionData['coupon_id'],
                    'created_at' => $transactionData['created_at'],
                ],
                $transactionData
            );
        }
    }
} 