<?php

namespace Database\Factories;

use App\Models\Beneficiary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Beneficiary>
 */
class BeneficiaryFactory extends Factory
{
    protected $model = Beneficiary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'national_id' => $this->faker->unique()->numerify('#########'),
            'family_members' => $this->faker->numberBetween(1, 12),
            'social_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'address' => $this->faker->optional(0.8)->address(),
        ];
    }
} 