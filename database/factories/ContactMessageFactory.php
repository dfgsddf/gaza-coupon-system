<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            'استفسار عن التسجيل',
            'مشكلة في النظام',
            'طلب مساعدة',
            'شكوى أو اقتراح',
            'استفسار عن الخدمات',
            'طلب دعم تقني',
            'الإبلاغ عن خطأ',
            'طلب معلومات إضافية'
        ];

        $messages = [
            'أرجو مساعدتي في فهم كيفية التسجيل في النظام والاستفادة من الخدمات المتاحة',
            'أواجه مشكلة في تسجيل الدخول إلى حسابي، أرجو المساعدة في حل هذه المشكلة',
            'أحتاج إلى معلومات إضافية حول شروط الاستفادة من المساعدات المقدمة',
            'لدي اقتراح لتحسين النظام وجعله أكثر سهولة في الاستخدام',
            'أرغب في معرفة المزيد عن الخدمات التي تقدمها المنظمة والمؤسسات المشاركة',
            'أواجه صعوبة في استخدام بعض الميزات في النظام، أرجو الإرشاد',
            'أشكركم على الخدمات المقدمة وأتمنى التوفيق في مساعدة المحتاجين',
            'هل يمكنني الحصول على معلومات حول كيفية التقديم للمساعدات الطارئة؟'
        ];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'subject' => $this->faker->randomElement($subjects),
            'message' => $this->faker->randomElement($messages),
            'status' => $this->faker->randomElement(['unread', 'read', 'replied']),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'unread',
            'created_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'read',
        ]);
    }

    public function replied(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'replied',
        ]);
    }
} 