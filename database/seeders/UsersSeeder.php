<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Movie;
use App\Models\Watchlist;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123456', 
            'profile_image' => 'https://robohash.org/admin',
            'member_since' => now(),
            'role' => 'admin',
        ]);


        User::create([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => '123456',
            'profile_image' => 'https://robohash.org/user',
            'member_since' => now(),
            'role' => 'user',  
        ]);
        
        $movies = Movie::all();
        
        if ($movies->isNotEmpty()) {
            $adminMovies = $movies->take(3);
            foreach ($adminMovies as $movie) {
                Watchlist::firstOrCreate([
                    'user_id' => $admin->user_id,
                    'movie_id' => $movie->movie_id,
                ]);
            }
            
        
            $userMovies = $movies->skip(3)->take(4);
            foreach ($userMovies as $movie) {
                Watchlist::firstOrCreate([
                    'user_id' => $user->user_id,
                    'movie_id' => $movie->movie_id,
                ]);
            }
            
            $this->command->info("Added movies to watchlists successfully!");
        } else {
            $this->command->warn("No movies found. Run MoviesSeeder first!");
        }
    }
}
