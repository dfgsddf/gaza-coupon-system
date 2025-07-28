<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $campaignNames = [
            'حملة إفطار صائم',
            'حملة كسوة الشتاء',
            'حملة العودة للمدارس',
            'حملة الأضاحي',
            'حملة رمضان الخير',
            'حملة دعم الأيتام',
            'حملة ترميم المنازل',
            'حملة الدعم الطبي',
            'حملة توزيع البطانيات',
            'حملة المساعدات الغذائية'
        ];

        $startDate = $this->faker->dateTimeBetween('-6 months', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+6 months');

        return [
            'charity_id' => User::factory()->asCharity(),
            'name' => $this->faker->randomElement($campaignNames),
            'description' => $this->faker->paragraph(),
            'goal' => $this->faker->numberBetween(5000, 100000),
            'current_amount' => $this->faker->numberBetween(0, 80000),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(['active', 'completed', 'paused', 'cancelled']),
            'image_url' => $this->faker->optional(0.3)->imageUrl(400, 300, 'charity'),
            'is_featured' => $this->faker->boolean(20), // 20% are featured
            'donors_count' => $this->faker->numberBetween(0, 500),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'start_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+3 months'),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'end_date' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
        ]);
    }
} 