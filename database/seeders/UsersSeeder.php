<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Movie;
use App\Models\Watchlist;
use App\Models\Rating;
use App\Models\Booking;
use App\Models\Showtime;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123456', 
            'profile_image' => 'https://robohash.org/admin',
            'member_since' => now(),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);


        $user = User::updateOrCreate([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => '123456',
            'profile_image' => 'https://robohash.org/user',
            'member_since' => now(),
            'role' => 'user',
            'email_verified_at' => now(),  
        ]);
        
        $movies = Movie::all();
        
        if ($movies->isNotEmpty()) {
            // First, clear existing watchlist, ratings, bookings for these users to avoid duplicates
            Watchlist::where('user_id', $admin->user_id)->delete();
            Watchlist::where('user_id', $user->user_id)->delete();
            Rating::where('user_id', $admin->user_id)->delete();
            Rating::where('user_id', $user->user_id)->delete();
            Booking::where('user_id', $admin->user_id)->delete();
            Booking::where('user_id', $user->user_id)->delete();
            
            // Admin watchlist (first 3 movies)
            $adminMovies = $movies->take(3);
            foreach ($adminMovies as $movie) {
                Watchlist::create([
                    'user_id' => $admin->user_id,
                    'movie_id' => $movie->movie_id,
                ]);
            }
            
            // Admin ratings
            $ratingsData = [
                ['score' => 5, 'comment' => 'Excellent movie! Highly recommended.'],
                ['score' => 4, 'comment' => 'Very good, enjoyed it a lot.'],
            ];
            $i = 0;
            foreach ($adminMovies->take(2) as $movie) {
                Rating::create([
                    'user_id' => $admin->user_id,
                    'movie_id' => $movie->movie_id,
                    'score' => $ratingsData[$i]['score'],
                    'comment' => $ratingsData[$i]['comment'],
                ]);
                $i++;
            }
            
            // User watchlist (next 4 movies)
            $userMovies = $movies->skip(3)->take(4);
            foreach ($userMovies as $movie) {
                Watchlist::create([
                    'user_id' => $user->user_id,
                    'movie_id' => $movie->movie_id,
                ]);
            }
            
            // User ratings
            $userRatingsData = [
                ['score' => 5, 'comment' => 'Masterpiece! Will watch again.'],
                ['score' => 3, 'comment' => 'Good but could be better.'],
                ['score' => 4, 'comment' => 'Really enjoyed this one!'],
            ];
            $i = 0;
            foreach ($userMovies->take(3) as $movie) {
                Rating::create([
                    'user_id' => $user->user_id,
                    'movie_id' => $movie->movie_id,
                    'score' => $userRatingsData[$i]['score'],
                    'comment' => $userRatingsData[$i]['comment'],
                ]);
                $i++;
            }
            
            // User bookings
            $showtimes = Showtime::all();
            if ($showtimes->isNotEmpty()) {
                Booking::create([
                    'user_id' => $user->user_id,
                    'showtime_id' => $showtimes[0]->showtime_id,
                    'seats' => 2,
                    'price' => 30.00,
                    'status' => 'confirmed',
                    'customer_info' => json_encode([
                        'name' => 'Test User',
                        'email' => 'user@gmail.com',
                        'phone' => '123456789',
                        'payment_method' => 'card',
                        'selected_seats' => ['A1', 'A2']
                    ]),
                    'booking_date' => now(),
                ]);
                
                Booking::create([
                    'user_id' => $user->user_id,
                    'showtime_id' => $showtimes->count() > 1 ? $showtimes[1]->showtime_id : $showtimes[0]->showtime_id,
                    'seats' => 4,
                    'price' => 60.00,
                    'status' => 'confirmed',
                    'customer_info' => json_encode([
                        'name' => 'Test User',
                        'email' => 'user@gmail.com',
                        'phone' => '123456789',
                        'payment_method' => 'cash',
                        'selected_seats' => ['B3', 'B4', 'B5', 'B6']
                    ]),
                    'booking_date' => now()->subDays(10),
                ]);
                
                $this->command->info("Added bookings for user successfully!");
            } else {
                $this->command->warn("No showtimes found.");
            }
            
            $this->command->info("Added movies to watchlists and ratings successfully!");
        } else {
            $this->command->warn("No movies found. Run MoviesSeeder first!");
        }
    }
}
