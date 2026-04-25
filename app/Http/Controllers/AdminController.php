<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Game;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request){
        $user=Auth::user();
        $movieCount = Movie::count();
        $movies = Movie::latest()->get();
        $gameCount = Game::count();
        $games =Game::latest()->get();
        $userCount = User::count();
        $users=User::latest()->get();
        $bookingCount= Booking::count();

        $bookingSearch = trim((string) $request->query('booking_search', ''));
        $bookingStatus = strtolower(trim((string) $request->query('booking_status', '')));

        $bookingsQuery = Booking::query()
            ->with(['user:user_id,username', 'showtime:showtime_id,movie_id', 'showtime.movie:movie_id,title,poster'])
            ->latest();

        if ($bookingStatus !== '') {
            $bookingsQuery->where('status', $bookingStatus);
        }

        if ($bookingSearch !== '') {
            $bookingsQuery->where(function ($query) use ($bookingSearch) {
                $query->where('booking_id', 'like', "%{$bookingSearch}%")
                    ->orWhereHas('user', function ($userQuery) use ($bookingSearch) {
                        $userQuery->where('username', 'like', "%{$bookingSearch}%");
                    })
                    ->orWhereHas('showtime.movie', function ($movieQuery) use ($bookingSearch) {
                        $movieQuery->where('title', 'like', "%{$bookingSearch}%");
                    });
            });
        }

        $bookings = $bookingsQuery
            ->paginate(50, ['*'], 'bookings_page')
            ->withQueryString()
            ->appends(['tab' => 'bookings']);

        // Keep status freshness bounded to the current page instead of scanning the entire table.
        Booking::syncWatchedStatuses($bookings->getCollection());

        return view('pages.admin', compact(
            'user',
            'movieCount',
            'movies',
            'gameCount',
            'userCount',
            'users',
            'bookingCount',
            'bookings',
            'games',
            'bookingSearch',
            'bookingStatus'
        ));
       
    }
    public function destroyMovie(Movie $movie){
        $movie->delete();
        return response()->json(['success'=>true]);
    }
    public function destroyGame(Game $game){
        $game->delete();
        return response()->json(['success'=>true]);
    }
    public function destroyUser(User $user){
       if ($user->user_id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }
        $user->delete();
        return response()->json(['success' => true]);
    }
    public function destroyBooking(Booking $booking){
        $booking->delete();
        return response()->json(['success'=>true]);
    }
}
