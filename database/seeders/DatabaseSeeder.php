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
        // استدعاء Seeder للصلاحيات
        $this->call(PermissionSeeder::class);

        // استدعاء Seeder للصلاحيات الخاصة بالجمعيات الخيرية
        $this->call(CharityPermissionSeeder::class);
        
        // استدعاء Seeder الخاص بالمستخدمين
        $this->call(UserSeeder::class);
        
        // استدعاء Seeder للبيانات التجريبية
        $this->call(DemoCouponSeeder::class);
        
        // استدعاء Seeder للمعاملات
        $this->call(TransactionSeeder::class);
        
        // استدعاء Seeder للحملات
        $this->call(CampaignSeeder::class);
    }
}
