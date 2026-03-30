<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Movie;

class UserWatchlistSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::where('username', 'admin')->first();
        $user2 = User::where('username', 'user')->first();

        if (!$user1 || !$user2) {
            $this->command->error('Users not found. Run UsersSeeder first.');
            return;
        }
        $movieIds = Movie::pluck('movie_id')->take(5)->toArray();

        
        $user1->watchlistedMovies()->syncWithoutDetaching($movieIds);

        
        $user2->watchlistedMovies()->syncWithoutDetaching(array_slice($movieIds, 0, 3));

        $this->command->info("Watchlist entries added for {$user1->username} and {$user2->username}.");
    }
}