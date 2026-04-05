<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileApiController extends Controller
{
    public function removeFromWatchlist(Request $request, $movie_id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $deleted = Watchlist::where('user_id', $user->user_id)
            ->where('movie_id', $movie_id)
            ->delete();
        
        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Movie removed from watchlist'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Movie not found in watchlist'
        ], 404);
    }
}