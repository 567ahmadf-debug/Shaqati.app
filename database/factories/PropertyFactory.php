<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'landlord_id' => User::factory()->landlord(),

            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),

            'property_type' => fake()->randomElement([
                'apartment',
                'house',
                'studio',
                'villa',
            ]),

            'rental_price' => fake()->randomFloat(2, 50, 1500),
            'currency' => 'USD',

            'area' => fake()->randomFloat(2, 30, 300),
            'bedrooms' => fake()->numberBetween(0, 6),
            'bathrooms' => fake()->numberBetween(1, 4),

            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement([
                'Damascus',
                'Aleppo',
                'Homs',
                'Latakia',
            ]),

            'latitude' => null,
            'longitude' => null,

            'status' => 'draft',
            'availability_status' => 'available',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    public function pendingReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending_review',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'availability_status' => 'unavailable',
        ]);
    }
}
