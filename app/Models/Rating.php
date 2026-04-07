<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $primaryKey = 'rating_id';

    protected $fillable = [
        'user_id',
        'movie_id',
        'score',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:1',
        ];
    }

    /**
     * Get the user who left this rating.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the movie that was rated.
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'movie_id');
    }

    /**
     * Recalculate movie rating after creating/updating a rating
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($rating) {
            $rating->updateMovieRating();
        });

        static::deleted(function ($rating) {
            $rating->updateMovieRating();
        });
    }

    /**
     * Update the movie's rating to the average of all user ratings
     */
    public function updateMovieRating()
    {
        if (!$this->movie_id) return;

        $averageRating = Rating::where('movie_id', $this->movie_id)
            ->avg('score');

        // Cap rating at 5.0 maximum and ensure non-negative
        $finalRating = max(0, min(round($averageRating ?? 0, 1), 5.0));

        Movie::where('movie_id', $this->movie_id)
            ->update(['rating' => $finalRating]);
    }
}
