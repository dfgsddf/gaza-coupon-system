<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Store;
use App\Models\Organization;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $transactionTypes = ['coupon_redemption', 'donation', 'purchase'];
        $statuses = ['pending', 'completed', 'cancelled'];

        return [
            'beneficiary_id' => User::factory()->asBeneficiary(),  // يشير إلى users
            'store_id' => User::factory()->asStoreUser(),          // يشير إلى users  
            'organization_id' => $this->faker->optional(0.7)->randomElement(Organization::pluck('id')->toArray() ?: [null]),
            'coupon_id' => Coupon::factory(),
            'transaction_type' => $this->faker->randomElement($transactionTypes),
            'coupon_value' => $this->faker->randomFloat(2, 10, 1000),
            'beneficiary_name' => $this->faker->name(),
            'store_name' => $this->faker->company(),
            'status' => $this->faker->randomElement($statuses),
            'redeemed_at' => $this->faker->optional(0.8)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the transaction is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'redeemed_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Indicate that the transaction is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'redeemed_at' => null,
        ]);
    }

    /**
     * Indicate that the transaction is a coupon redemption.
     */
    public function couponRedemption(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'coupon_redemption',
            'status' => $this->faker->randomElement(['completed', 'pending']),
        ]);
    }

    /**
     * Indicate that the transaction is a donation.
     */
    public function donation(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'donation',
            'coupon_value' => $this->faker->randomFloat(2, 50, 5000),
            'store_id' => null,
            'coupon_id' => null,
            'beneficiary_name' => 'متبرع كريم',
        ]);
    }

    /**
     * High value transaction.
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'coupon_value' => $this->faker->randomFloat(2, 500, 3000),
        ]);
    }

    /**
     * Recent transaction.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'redeemed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'status' => 'completed',
        ]);
    }
} 