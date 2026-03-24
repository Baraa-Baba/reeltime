<?php

namespace Database\Seeders;

use App\Models\HeroBanner;
use Illuminate\Database\Seeder;

class HeroBannerSeeder extends Seeder
{
    public function run(): void
    {
        HeroBanner::truncate();

        // 1) Main welcome banner (matches current static hero)
        HeroBanner::create([
            'title'            => 'Welcome to ReelTime',
            'subtitle'         => 'YOUR MOVIES IN ONE PLACE',
            'cta_label'        => 'Learn More',
            'cta_route_name'   => 'about',
            'background_image' => 'imgs/for welcome page.png', // adjust if your file name differs
            'position'         => 1,
        ]);

        // 2) Discover movies banner
        HeroBanner::create([
            'title'            => 'Discover Your Next Favorite Movie',
            'subtitle'         => 'Search by title, genre, or mood and build your watchlist',
            'cta_label'        => 'Search Movies',
            'cta_route_name'   => 'search',
            'background_image' => 'imgs/discover-movies.png', // use an existing image or add one
            'position'         => 2,
        ]);

        // 3) Book and Play banner
        HeroBanner::create([
            'title'            => 'Book Shows & Play Movie Trivia',
            'subtitle'         => 'Reserve seats, track bookings, and challenge your friends',
            'cta_label'        => 'Book Now',
            'cta_route_name'   => 'bookings',
            'background_image' => 'imgs/book-and-play.png', // use an existing image or add one
            'position'         => 3,
        ]);
    }
}