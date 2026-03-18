<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'trailer_link' => 'https://www.youtube.com/watch?v=' . fake()->lexify('???????????'),
            'cast' => implode(', ', fake()->words(5)),
            'genres' => implode(', ', fake()->words(3)),
            'rating' => fake()->randomFloat(1, 1, 10),
            'duration' => fake()->numberBetween(80, 180),
            'poster' => fake()->imageUrl(300, 450, 'movies'),
        ];
    }
}
