<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعاء Seeder الخاص بالمستخدمين
        $this->call(UserSeeder::class);
        
        // استدعاء Seeder للبيانات التجريبية
        $this->call(DemoCouponSeeder::class);
        
        // استدعاء Seeder للمعاملات
        $this->call(TransactionSeeder::class);
        
        // استدعاء Seeder للحملات
        $this->call(CampaignSeeder::class);
        
        // استدعاء Seeder للصلاحيات
        $this->call(CharityPermissionSeeder::class);
    }
}
