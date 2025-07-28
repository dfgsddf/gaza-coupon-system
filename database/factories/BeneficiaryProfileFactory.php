<?php

namespace Database\Factories;

use App\Models\BeneficiaryProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BeneficiaryProfile>
 */
class BeneficiaryProfileFactory extends Factory
{
    protected $model = BeneficiaryProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = ['male', 'female'];
        $maritalStatuses = ['single', 'married', 'divorced', 'widowed'];
        $incomeLevels = ['no_income', 'very_low', 'low', 'below_average', 'average'];
        $housingTypes = ['owned', 'rented', 'shared', 'temporary', 'refugee_camp'];
        $employmentStatuses = ['unemployed', 'part_time', 'full_time', 'retired', 'student'];
        $educationLevels = ['no_education', 'primary', 'secondary', 'high_school', 'diploma', 'bachelor', 'master', 'phd'];
        $verificationStatuses = ['pending', 'verified', 'rejected'];

        $medicalConditions = [
            'مرض السكري',
            'ضغط الدم',
            'أمراض القلب',
            'الكلى',
            'إعاقة حركية',
            'إعاقة بصرية',
            'إعاقة سمعية',
            'أمراض مزمنة أخرى'
        ];

        $specialNeeds = [
            'كرسي متحرك',
            'أدوية مزمنة',
            'نظارات طبية',
            'أجهزة مساعدة للسمع',
            'رعاية طبية خاصة',
            'غذاء خاص',
            'مساعدة في الحركة'
        ];

        $professions = [
            'عامل بناء',
            'معلم',
            'طبيب',
            'مهندس',
            'تاجر',
            'خياط',
            'نجار',
            'كهربائي',
            'سائق',
            'عامل نظافة',
            'موظف حكومي',
            'ربة منزل'
        ];

        return [
            'user_id' => User::factory(), // سيتم ربطه لاحقاً
            'id_number' => $this->faker->unique()->numerify('#########'),
            'date_of_birth' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'gender' => $this->faker->randomElement($genders),
            'marital_status' => $this->faker->randomElement($maritalStatuses),
            'family_members' => $this->faker->numberBetween(1, 12),
            'income_level' => $this->faker->randomElement($incomeLevels),
            'housing_type' => $this->faker->randomElement($housingTypes),
            'medical_condition' => $this->faker->optional(0.4)->randomElement($medicalConditions),
            'special_needs' => $this->faker->optional(0.3)->randomElement($specialNeeds),
            'employment_status' => $this->faker->randomElement($employmentStatuses),
            'profession' => $this->faker->optional(0.7)->randomElement($professions),
            'education_level' => $this->faker->randomElement($educationLevels),
            'documents' => $this->faker->optional(0.8)->randomElements([
                'national_id' => 'documents/national_id_' . $this->faker->uuid() . '.pdf',
                'income_certificate' => 'documents/income_' . $this->faker->uuid() . '.pdf',
                'medical_report' => 'documents/medical_' . $this->faker->uuid() . '.pdf',
                'housing_certificate' => 'documents/housing_' . $this->faker->uuid() . '.pdf'
            ], $this->faker->numberBetween(1, 4)),
            'notes' => $this->faker->optional(0.6)->paragraph(),
            'verification_status' => $this->faker->randomElement($verificationStatuses),
            'verified_by' => null, // سيتم تعيينه للمؤكدين
            'verified_at' => null, // سيتم تعيينه للمؤكدين
        ];
    }

    /**
     * Indicate that the beneficiary is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'verified',
            'verified_by' => User::where('role', 'admin')->first()?->id ?? 1,
            'verified_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Indicate that the beneficiary is pending verification.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'pending',
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }

    /**
     * Indicate that the beneficiary is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => 'rejected',
            'verified_by' => User::where('role', 'admin')->first()?->id ?? 1,
            'verified_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Indicate that the beneficiary is a large family.
     */
    public function largeFamily(): static
    {
        return $this->state(fn (array $attributes) => [
            'family_members' => $this->faker->numberBetween(8, 15),
            'housing_type' => $this->faker->randomElement(['rented', 'shared', 'temporary']),
            'income_level' => $this->faker->randomElement(['no_income', 'very_low', 'low']),
        ]);
    }

    /**
     * Indicate that the beneficiary has special needs.
     */
    public function withSpecialNeeds(): static
    {
        return $this->state(fn (array $attributes) => [
            'medical_condition' => $this->faker->randomElement([
                'إعاقة حركية',
                'إعاقة بصرية',
                'إعاقة سمعية',
                'مرض السكري',
                'أمراض القلب'
            ]),
            'special_needs' => $this->faker->randomElement([
                'كرسي متحرك',
                'أدوية مزمنة',
                'نظارات طبية',
                'أجهزة مساعدة للسمع',
                'رعاية طبية خاصة'
            ]),
        ]);
    }

    /**
     * Indicate that the beneficiary is unemployed.
     */
    public function unemployed(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_status' => 'unemployed',
            'profession' => null,
            'income_level' => $this->faker->randomElement(['no_income', 'very_low']),
        ]);
    }

    /**
     * Indicate that the beneficiary is elderly.
     */
    public function elderly(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_of_birth' => $this->faker->dateTimeBetween('-85 years', '-65 years'),
            'employment_status' => 'retired',
            'medical_condition' => $this->faker->randomElement([
                'ضغط الدم',
                'مرض السكري',
                'أمراض القلب',
                'أمراض مزمنة أخرى'
            ]),
        ]);
    }

    /**
     * Indicate that the beneficiary is a single mother.
     */
    public function singleMother(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'female',
            'marital_status' => $this->faker->randomElement(['divorced', 'widowed']),
            'family_members' => $this->faker->numberBetween(2, 8),
            'employment_status' => $this->faker->randomElement(['unemployed', 'part_time']),
            'income_level' => $this->faker->randomElement(['no_income', 'very_low', 'low']),
        ]);
    }
} 