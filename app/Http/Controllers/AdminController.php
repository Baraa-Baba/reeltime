<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Game;
use App\Models\User;
use App\Models\Booking;

class AdminController extends Controller
{
    public function index(){
        $movieCount = Movie::count();
        $movies = Movie::latest()->get();
        $gameCount = Game::count();
        $games =Game::latest()->get();
        $userCount = User::count();
        $users=User::latest()->get();
        $bookingCount= Booking::count();
        return view('pages.admin', compact('movieCount','movies','gameCount','userCount','users','bookingCount','games'));
       
    }
}
