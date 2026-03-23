<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Watchlist>
 */
class WatchlistFactory extends Factory
{
    public function definition(): array
    {
        $userId  = User::query()->inRandomOrder()->value('user_id')  ?? User::factory();
        $movieId = Movie::query()->inRandomOrder()->value('movie_id') ?? Movie::factory();

        return [
            'user_id'  => $userId,
            'movie_id' => $movieId,
        ];
    }
}