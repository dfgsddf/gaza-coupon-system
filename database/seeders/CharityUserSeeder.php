<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CharityUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم جمعية خيرية تجريبي
        User::create([
            'name' => 'جمعية الخير للأعمال الخيرية',
            'email' => 'charity@example.com',
            'password' => Hash::make('password'),
            'role' => 'charity',
            'status' => 'active',
            'phone' => '0123456789',
            'address' => 'غزة، فلسطين',
        ]);

        // إنشاء جمعية خيرية أخرى للتجربة
        User::create([
            'name' => 'مؤسسة الأمل الخيرية',
            'email' => 'amal@charity.com',
            'password' => Hash::make('123456789'),
            'role' => 'charity',
            'status' => 'active',
            'phone' => '0987654321',
            'address' => 'رفح، غزة',
        ]);
    }
}
