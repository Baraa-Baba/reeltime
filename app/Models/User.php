<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'member_since' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the ratings by this user.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id', 'user_id');
    }

    /**
     * Get the watchlist entries for this user.
     */
    public function watchlists()
    {
        return $this->hasMany(Watchlist::class, 'user_id', 'user_id');
    }

    /**
     * Get the bookings made by this user.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'user_id');
    }

    /**
     * Get the game rounds played by this user.
     */
    public function gameRounds()
    {
        return $this->hasMany(GameRound::class, 'user_id', 'user_id');
    }

    /**
     * Get the movies this user has on their watchlist.
     */
    public function watchlistedMovies()
    {
        return $this->belongsToMany(Movie::class, 'watchlists', 'user_id', 'movie_id');
    }

    /**
     * Get the movies this user has rated.
     */
    public function ratedMovies()
    {
        return $this->belongsToMany(Movie::class, 'ratings', 'user_id', 'movie_id')
                    ->withPivot('score', 'comment');
    }
}
