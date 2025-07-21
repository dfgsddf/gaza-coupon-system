<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Store User',
                'email' => 'store@example.com',
                'password' => Hash::make('password'),
                'role' => 'store',
            ],
            [
                'name' => 'Beneficiary User',
                'email' => 'beneficiary@example.com',
                'password' => Hash::make('password'),
                'role' => 'beneficiary',
            ],
            [
                'name' => 'Charity User',
                'email' => 'charity@example.com',
                'password' => Hash::make('password'),
                'role' => 'charity',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
