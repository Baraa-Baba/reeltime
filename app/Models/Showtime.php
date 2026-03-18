<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $primaryKey = 'showtime_id';

    protected $fillable = [
        'movie_id',
        'cinema_id',
        'show_date',
        'show_time',
        'available_seats',
        'price_seat',
    ];

    protected function casts(): array
    {
        return [
            'show_date' => 'date',
            'show_time' => 'datetime:H:i',
            'available_seats' => 'integer',
            'price_seat' => 'decimal:2',
        ];
    }

    /**
     * Get the movie for this showtime.
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'movie_id');
    }

    /**
     * Get the cinema for this showtime.
     */
    public function cinema()
    {
        return $this->belongsTo(Cinema::class, 'cinema_id', 'cinema_id');
    }

    /**
     * Get the bookings for this showtime.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'showtime_id', 'showtime_id');
    }
}
