<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'CPN' . time() . strtoupper($this->faker->unique()->bothify('###')),
            'user_id' => function() {
                // إنشاء مستفيد (مستخدم بدور beneficiary)
                return User::factory()->create(['role' => 'beneficiary'])->id;
            },
            'value' => $this->faker->randomFloat(2, 50, 1000),
            'description' => $this->faker->sentence(),
            'store_name' => $this->faker->company(),
            'expiry_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'redeemed' => $this->faker->boolean(30), // 30% are redeemed
            'redeemed_at' => null,
        ];
    }

    /**
     * Indicate that the coupon is redeemed.
     */
    public function redeemed(): static
    {
        return $this->state(fn (array $attributes) => [
            'redeemed' => true,
            'redeemed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Indicate that the coupon is not redeemed.
     */
    public function notRedeemed(): static
    {
        return $this->state(fn (array $attributes) => [
            'redeemed' => false,
            'redeemed_at' => null,
        ]);
    }

    /**
     * Indicate that the coupon is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiry_date' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            'redeemed' => false,
            'redeemed_at' => null,
        ]);
    }

    /**
     * High value coupon.
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'value' => $this->faker->randomFloat(2, 500, 2000),
        ]);
    }
} 