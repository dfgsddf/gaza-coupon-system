<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arabicNames = [
            'أحمد محمد',
            'فاطمة علي',
            'محمود حسن',
            'مريم يوسف',
            'عبد الله صالح',
            'خديجة أحمد',
            'إبراهيم محمد',
            'نور الدين عبد الرحمن',
            'عائشة سليمان',
            'محمد عبد الله',
            'زينب إبراهيم',
            'يوسف محمد',
            'حفصة أحمد',
            'عمر علي',
            'رقية حسن'
        ];

        return [
            'name' => $this->faker->randomElement($arabicNames),
            'email' => fake()->unique()->safeEmail(),
            'phone' => $this->faker->optional(0.8)->phoneNumber(),
            'address' => $this->faker->optional(0.7)->address(),
            'role' => $this->faker->randomElement(['admin', 'store', 'beneficiary', 'charity']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function asAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is a store owner.
     */
    public function asStoreUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'store',
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is a beneficiary.
     */
    public function asBeneficiary(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'beneficiary',
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is a charity organization member.
     */
    public function asCharity(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'charity',
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the user is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }
}
