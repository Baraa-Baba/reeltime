<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Showtime>
 */
class ShowtimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => \App\Models\Movie::factory(),
            'cinema_id' => \App\Models\Cinema::factory(),
            'show_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'show_time' => fake()->time(),
            'available_seats' => fake()->numberBetween(50, 200),
            'price_seat' => fake()->randomFloat(2, 5, 25),
        ];
    }
}
