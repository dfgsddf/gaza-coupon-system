<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\User;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get charity user
        $charity = User::where('role', 'charity')->first();
        
        if (!$charity) {
            $this->command->error('No charity user found. Please run the CharityPermissionSeeder first.');
            return;
        }

        $campaigns = [
            [
                'name' => 'Emergency Relief for Gaza Families',
                'description' => 'Providing immediate assistance to families affected by the crisis in Gaza. This campaign focuses on food, shelter, and medical supplies.',
                'goal' => 50000.00,
                'current_amount' => 32500.00,
                'status' => 'active',
                'charity_id' => $charity->id,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(60),
                'is_featured' => true,
                'donors_count' => 156,
            ],
            [
                'name' => 'Medical Supplies for Hospitals',
                'description' => 'Supporting hospitals and medical facilities with essential supplies and equipment to treat patients in need.',
                'goal' => 25000.00,
                'current_amount' => 18750.00,
                'status' => 'active',
                'charity_id' => $charity->id,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(45),
                'is_featured' => false,
                'donors_count' => 89,
            ],
            [
                'name' => 'Education Support Program',
                'description' => 'Helping children continue their education by providing school supplies, books, and online learning resources.',
                'goal' => 15000.00,
                'current_amount' => 15000.00,
                'status' => 'completed',
                'charity_id' => $charity->id,
                'start_date' => now()->subDays(90),
                'end_date' => now()->subDays(10),
                'is_featured' => false,
                'donors_count' => 234,
            ],
            [
                'name' => 'Clean Water Initiative',
                'description' => 'Installing water purification systems and providing clean drinking water to communities in need.',
                'goal' => 30000.00,
                'current_amount' => 12000.00,
                'status' => 'active',
                'charity_id' => $charity->id,
                'start_date' => now()->subDays(7),
                'end_date' => now()->addDays(90),
                'is_featured' => true,
                'donors_count' => 67,
            ],
            [
                'name' => 'Winter Clothing Drive',
                'description' => 'Providing warm clothing and blankets to families during the cold winter months.',
                'goal' => 10000.00,
                'current_amount' => 8500.00,
                'status' => 'active',
                'charity_id' => $charity->id,
                'start_date' => now()->subDays(20),
                'end_date' => now()->addDays(30),
                'is_featured' => false,
                'donors_count' => 123,
            ],
        ];

        foreach ($campaigns as $campaignData) {
            Campaign::create($campaignData);
        }

        $this->command->info('Campaigns seeded successfully!');
    }
}
