<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'showtime_id' => \App\Models\Showtime::factory(),
            'seats' => fake()->numberBetween(1, 5),
            'price' => fake()->randomFloat(2, 10, 100),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
            'customer_info' => fake()->paragraph(),
            'booking_date' => now(),
        ];
    }
}
