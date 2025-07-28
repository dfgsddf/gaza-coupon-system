<?php

namespace Database\Factories;

use App\Models\CharityOrganization;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CharityOrganization>
 */
class CharityOrganizationFactory extends Factory
{
    protected $model = CharityOrganization::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            'إغاثة طارئة',
            'توزيع مواد غذائية',
            'رعاية صحية',
            'تعليم وتدريب',
            'دعم الأيتام',
            'كفالة العائلات',
            'مساعدات مالية',
            'ترميم المنازل',
            'توفير المياه النظيفة',
            'دعم نفسي واجتماعي',
            'برامج التأهيل',
            'مشاريع تنموية'
        ];

        $missionStatements = [
            'تقديم الإغاثة والمساعدة للأسر المحتاجة في قطاع غزة وتحسين أوضاعهم المعيشية',
            'العمل على تخفيف المعاناة عن الأسر الفقيرة وتوفير احتياجاتهم الأساسية',
            'تقديم الدعم الشامل للمجتمع من خلال برامج الإغاثة والتنمية المستدامة',
            'مساعدة الأسر المتضررة وتقديم الدعم اللازم لتحسين ظروفهم الاجتماعية والاقتصادية',
            'العمل على بناء مجتمع متماسك من خلال برامج الإغاثة والدعم الاجتماعي'
        ];

        $visionStatements = [
            'مجتمع خالٍ من الفقر والحاجة، يتمتع فيه كل فرد بحياة كريمة',
            'أن نكون المؤسسة الرائدة في مجال الإغاثة والتنمية في فلسطين',
            'بناء مجتمع قوي ومستقل قادر على مواجهة التحديات والصعوبات',
            'تحقيق العدالة الاجتماعية والكرامة الإنسانية لجميع أفراد المجتمع',
            'مستقبل مشرق للأجيال القادمة من خلال التنمية المستدامة'
        ];

        $contactPersons = [
            'أحمد محمد الفلسطيني',
            'فاطمة علي السلامة',
            'محمود حسن العيسى',
            'مريم يوسف الغزي',
            'عبد الله صالح النجار',
            'خديجة أحمد الشاعر',
            'إبراهيم محمد القدومي',
            'نور الدين عبد الرحمن'
        ];

        return [
            'organization_id' => Organization::factory()->charity(),
            'registration_number' => 'REG' . $this->faker->unique()->numerify('######'),
            'license_number' => 'LIC' . $this->faker->unique()->numerify('######'),
            'license_expiry_date' => $this->faker->dateTimeBetween('now', '+3 years'),
            'mission_statement' => $this->faker->randomElement($missionStatements),
            'vision_statement' => $this->faker->randomElement($visionStatements),
            'services' => implode(',', $this->faker->randomElements($services, $this->faker->numberBetween(3, 8))),
            'contact_person' => $this->faker->randomElement($contactPersons),
            'website' => $this->faker->optional(0.6)->url(),
            'bank_account' => $this->faker->bankAccountNumber(),
            'bank_name' => $this->faker->randomElement([
                'بنك فلسطين',
                'البنك الإسلامي الفلسطيني',
                'بنك الاستثمار الفلسطيني',
                'البنك التجاري الفلسطيني',
                'بنك القدس',
                'البنك الوطني الإسلامي'
            ]),
        ];
    }

    /**
     * Indicate that the charity has a valid license.
     */
    public function withValidLicense(): static
    {
        return $this->state(fn (array $attributes) => [
            'license_expiry_date' => $this->faker->dateTimeBetween('+1 month', '+3 years'),
        ]);
    }

    /**
     * Indicate that the charity has an expired license.
     */
    public function withExpiredLicense(): static
    {
        return $this->state(fn (array $attributes) => [
            'license_expiry_date' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }

    /**
     * Indicate that the charity license is expiring soon.
     */
    public function withExpiringSoonLicense(): static
    {
        return $this->state(fn (array $attributes) => [
            'license_expiry_date' => $this->faker->dateTimeBetween('now', '+30 days'),
        ]);
    }

    /**
     * Indicate that the charity has no license.
     */
    public function withoutLicense(): static
    {
        return $this->state(fn (array $attributes) => [
            'license_number' => null,
            'license_expiry_date' => null,
        ]);
    }

    /**
     * Indicate that the charity focuses on emergency relief.
     */
    public function emergencyRelief(): static
    {
        return $this->state(fn (array $attributes) => [
            'services' => 'إغاثة طارئة,توزيع مواد غذائية,مساعدات مالية,توفير المياه النظيفة',
            'mission_statement' => 'تقديم الإغاثة الطارئة والمساعدة الفورية للأسر المتضررة في حالات الطوارئ والأزمات',
        ]);
    }

    /**
     * Indicate that the charity focuses on healthcare.
     */
    public function healthcare(): static
    {
        return $this->state(fn (array $attributes) => [
            'services' => 'رعاية صحية,دعم نفسي واجتماعي,برامج التأهيل,توفير الأدوية',
            'mission_statement' => 'تقديم الرعاية الصحية الشاملة والدعم النفسي للمحتاجين في المجتمع',
        ]);
    }

    /**
     * Indicate that the charity focuses on education.
     */
    public function education(): static
    {
        return $this->state(fn (array $attributes) => [
            'services' => 'تعليم وتدريب,دعم الأيتام,مشاريع تنموية,برامج التأهيل المهني',
            'mission_statement' => 'توفير التعليم والتدريب المهني لبناء قدرات الشباب وتأهيلهم لسوق العمل',
        ]);
    }

    /**
     * Indicate that the charity focuses on orphan care.
     */
    public function orphanCare(): static
    {
        return $this->state(fn (array $attributes) => [
            'services' => 'دعم الأيتام,كفالة العائلات,تعليم وتدريب,دعم نفسي واجتماعي',
            'mission_statement' => 'رعاية الأيتام وكفالتهم وتوفير بيئة آمنة ومحبة لنموهم وتطورهم',
        ]);
    }
} 