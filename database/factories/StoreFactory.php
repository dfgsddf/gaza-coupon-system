<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $storeTypes = ['grocery', 'pharmacy', 'restaurant', 'clothing', 'electronics', 'other'];
        $storeNames = [
            'بقالة الأمل',
            'صيدلية النور',
            'مطعم الأصايل',
            'محل الياسمين للألبسة',
            'معرض التقنية',
            'بقالة الخير',
            'صيدلية الشفاء',
            'مطعم البيت',
            'أزياء المدينة',
            'عالم الإلكترونيات',
            'سوبر ماركت العائلة',
            'صيدلية الحياة'
        ];

        return [
            'name' => $this->faker->randomElement($storeNames),
            'store_code' => 'STR' . time() . $this->faker->unique()->numberBetween(1, 999),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'has_physical_location' => $this->faker->boolean(80), // 80% have physical location
            'accepts_online_orders' => $this->faker->boolean(40), // 40% accept online orders
            'tax_number' => $this->faker->optional(0.7)->numerify('##########'),
            'commercial_register' => $this->faker->optional(0.6)->numerify('########'),
            'description' => $this->faker->optional(0.8)->paragraph(),
            'store_type' => $this->faker->randomElement($storeTypes),
            'location_lat' => $this->faker->optional(0.6)->latitude(31.3, 31.6), // Gaza coordinates
            'location_lng' => $this->faker->optional(0.6)->longitude(34.2, 34.5),
            'logo' => $this->faker->optional(0.3)->imageUrl(200, 200, 'business'),
        ];
    }

    /**
     * Indicate that the store is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the store is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate that the store has physical location.
     */
    public function withPhysicalLocation(): static
    {
        return $this->state(fn (array $attributes) => [
            'has_physical_location' => true,
            'location_lat' => $this->faker->latitude(31.3, 31.6),
            'location_lng' => $this->faker->longitude(34.2, 34.5),
        ]);
    }

    /**
     * Indicate that the store accepts online orders.
     */
    public function withOnlineOrders(): static
    {
        return $this->state(fn (array $attributes) => [
            'accepts_online_orders' => true,
        ]);
    }

    /**
     * Indicate that the store is a grocery store.
     */
    public function grocery(): static
    {
        return $this->state(fn (array $attributes) => [
            'store_type' => 'grocery',
            'name' => $this->faker->randomElement(['بقالة الأمل', 'بقالة الخير', 'سوبر ماركت العائلة']),
        ]);
    }

    /**
     * Indicate that the store is a pharmacy.
     */
    public function pharmacy(): static
    {
        return $this->state(fn (array $attributes) => [
            'store_type' => 'pharmacy',
            'name' => $this->faker->randomElement(['صيدلية النور', 'صيدلية الشفاء', 'صيدلية الحياة']),
        ]);
    }

    /**
     * Indicate that the store is a restaurant.
     */
    public function restaurant(): static
    {
        return $this->state(fn (array $attributes) => [
            'store_type' => 'restaurant',
            'name' => $this->faker->randomElement(['مطعم الأصايل', 'مطعم البيت', 'مطعم الكرم']),
            'accepts_online_orders' => true,
        ]);
    }
} 