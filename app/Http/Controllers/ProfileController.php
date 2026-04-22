<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('home')->with('login_required', true);
        }

        $watchlist = $user->watchlistedMovies()->with('ratings')->get();
        $ratedMovies = $user->ratings()->with('movie')->get();
        $bookings = $user->bookings()->with('showtime.movie', 'showtime.cinema')->get();
        Booking::syncWatchedStatuses($bookings);

        return view('pages.profile', compact('user', 'watchlist', 'ratedMovies', 'bookings'));
    }
}
    
