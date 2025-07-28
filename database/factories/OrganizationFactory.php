<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $organizationTypes = ['charity', 'government', 'non_profit', 'international'];
        $organizationNames = [
            'جمعية الإغاثة الإسلامية',
            'منظمة الأمل للتنمية',
            'مؤسسة الخير الاجتماعية',
            'جمعية النور الخيرية',
            'منظمة الحياة للإغاثة',
            'مؤسسة التضامن',
            'جمعية البر والتقوى',
            'منظمة الكرامة',
            'مؤسسة الرحمة',
            'جمعية الفلاح',
            'منظمة النهضة',
            'مؤسسة الوفاء'
        ];

        return [
            'name' => $this->faker->randomElement($organizationNames),
            'organization_code' => 'ORG' . time() . $this->faker->unique()->numberBetween(1, 999),
            'type' => $this->faker->randomElement($organizationTypes), // الحقل القديم
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'registration_date' => $this->faker->dateTimeBetween('-5 years', '-1 month'),
            'description' => $this->faker->optional(0.9)->paragraph(),
            'organization_type' => $this->faker->randomElement($organizationTypes),
            'logo' => $this->faker->optional(0.4)->imageUrl(200, 200, 'business'),
            'is_active' => $this->faker->boolean(85), // 85% are active
        ];
    }

    /**
     * Indicate that the organization is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the organization is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the organization is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the organization is a charity.
     */
    public function charity(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'charity',
            'organization_type' => 'charity',
            'name' => $this->faker->randomElement([
                'جمعية الإغاثة الإسلامية',
                'جمعية النور الخيرية',
                'جمعية البر والتقوى',
                'مؤسسة الخير الاجتماعية'
            ]),
        ]);
    }

    /**
     * Indicate that the organization is governmental.
     */
    public function government(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'government',
            'organization_type' => 'government',
            'name' => $this->faker->randomElement([
                'وزارة الشؤون الاجتماعية',
                'دائرة الإغاثة الحكومية',
                'مكتب رئيس الوزراء'
            ]),
        ]);
    }

    /**
     * Indicate that the organization is non-profit.
     */
    public function nonProfit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'non_profit',
            'organization_type' => 'non_profit',
            'name' => $this->faker->randomElement([
                'منظمة الأمل للتنمية',
                'منظمة الحياة للإغاثة',
                'منظمة الكرامة',
                'منظمة النهضة'
            ]),
        ]);
    }

    /**
     * Indicate that the organization is international.
     */
    public function international(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'international',
            'organization_type' => 'international',
            'name' => $this->faker->randomElement([
                'الصليب الأحمر الدولي',
                'منظمة الأمم المتحدة',
                'اليونيسف',
                'الوكالة الدولية للإغاثة'
            ]),
        ]);
    }
} 