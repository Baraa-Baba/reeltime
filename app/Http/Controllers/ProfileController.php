<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('home')->with('login_required', true);
        }

        $watchlist = $user->watchlistedMovies()->get();
        $ratedMovies = $user->ratings()->with('movie')->get();
        $bookings = $user->bookings()->with('showtime.movie')->get();

        return view('pages.profile', compact('user', 'watchlist', 'ratedMovies', 'bookings'));
    }
}
    
