<?php

namespace Database\Factories;

use App\Models\RequestModel;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestModel>
 */
class RequestModelFactory extends Factory
{
    protected $model = RequestModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $requestTypes = [
            'food_assistance',
            'medical_assistance', 
            'housing_assistance',
            'educational_assistance',
            'emergency_aid',
            'financial_assistance',
            'clothing_assistance',
            'utility_assistance'
        ];

        $requestDescriptions = [
            'food_assistance' => [
                'طلب مساعدة غذائية للأسرة المكونة من عدة أفراد في ظل الظروف الاقتصادية الصعبة',
                'نحتاج إلى مواد غذائية أساسية مثل الأرز والسكر والزيت والدقيق لتغطية احتياجات الأسرة',
                'تقديم طلب للحصول على سلة غذائية شهرية لمساعدة الأسرة في توفير الطعام اللازم',
            ],
            'medical_assistance' => [
                'طلب مساعدة طبية لعلاج مرض مزمن يتطلب أدوية باهظة الثمن',
                'نحتاج إلى مساعدة لتغطية تكاليف العلاج الطبي والفحوصات اللازمة',
                'طلب مساعدة للحصول على نظارات طبية أو أجهزة مساعدة للإعاقة',
            ],
            'housing_assistance' => [
                'طلب مساعدة لترميم المنزل المتضرر من الأحداث الأخيرة',
                'نحتاج إلى مساعدة لدفع إيجار المنزل بسبب عدم توفر دخل ثابت',
                'طلب مساعدة لإصلاح السقف والنوافذ التالفة في المنزل',
            ],
            'educational_assistance' => [
                'طلب مساعدة لدفع الرسوم المدرسية وشراء الكتب والقرطاسية للأطفال',
                'نحتاج إلى مساعدة لتوفير زي مدرسي وحقائب للطلاب',
                'طلب مساعدة لتسجيل الطلاب في دورات تقوية أو برامج تعليمية',
            ],
            'emergency_aid' => [
                'طلب مساعدة طارئة بسبب فقدان مصدر الدخل الوحيد للأسرة',
                'نحتاج إلى مساعدة عاجلة لمواجهة أزمة صحية أو اجتماعية طارئة',
                'طلب مساعدة فورية لتغطية احتياجات أساسية عاجلة',
            ]
        ];

        $statuses = ['pending', 'processing', 'approved', 'rejected', 'completed'];
        $categories = ['urgent', 'normal', 'low_priority'];

        $selectedType = $this->faker->randomElement($requestTypes);

        return [
            'user_id' => User::factory(),
            'type' => $selectedType,
            'description' => $this->faker->randomElement($requestDescriptions[$selectedType] ?? ['طلب مساعدة عامة من المؤسسة']),
            'status' => $this->faker->randomElement($statuses),
        ];
    }

    /**
     * Indicate that the request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the request is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the request is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    /**
     * Indicate that the request is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    /**
     * Indicate that the request is urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'emergency_aid',
        ]);
    }

    /**
     * Indicate that the request is for food assistance.
     */
    public function foodAssistance(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'food_assistance',
            'description' => $this->faker->randomElement([
                'طلب مساعدة غذائية للأسرة المكونة من عدة أفراد في ظل الظروف الاقتصادية الصعبة',
                'نحتاج إلى مواد غذائية أساسية مثل الأرز والسكر والزيت والدقيق لتغطية احتياجات الأسرة',
                'تقديم طلب للحصول على سلة غذائية شهرية لمساعدة الأسرة في توفير الطعام اللازم',
            ]),
        ]);
    }

    /**
     * Indicate that the request is for medical assistance.
     */
    public function medicalAssistance(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'medical_assistance',
            'description' => $this->faker->randomElement([
                'طلب مساعدة طبية لعلاج مرض مزمن يتطلب أدوية باهظة الثمن',
                'نحتاج إلى مساعدة لتغطية تكاليف العلاج الطبي والفحوصات اللازمة',
                'طلب مساعدة للحصول على نظارات طبية أو أجهزة مساعدة للإعاقة',
            ]),
        ]);
    }

    /**
     * Indicate that the request is for housing assistance.
     */
    public function housingAssistance(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'housing_assistance',
            'description' => $this->faker->randomElement([
                'طلب مساعدة لترميم المنزل المتضرر من الأحداث الأخيرة',
                'نحتاج إلى مساعدة لدفع إيجار المنزل بسبب عدم توفر دخل ثابت',
                'طلب مساعدة لإصلاح السقف والنوافذ التالفة في المنزل',
            ]),
        ]);
    }

    /**
     * Indicate that the request is for educational assistance.
     */
    public function educationalAssistance(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'educational_assistance',
            'description' => $this->faker->randomElement([
                'طلب مساعدة لدفع الرسوم المدرسية وشراء الكتب والقرطاسية للأطفال',
                'نحتاج إلى مساعدة لتوفير زي مدرسي وحقائب للطلاب',
                'طلب مساعدة لتسجيل الطلاب في دورات تقوية أو برامج تعليمية',
            ]),
        ]);
    }
} 