<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'user_id',
        'showtime_id',
        'seats',
        'price',
        'status',
        'customer_info',
        'booking_date',
    ];

    protected function casts(): array
    {
        return [
            'seats' => 'integer',
            'price' => 'decimal:2',
            'booking_date' => 'datetime',
        ];
    }

    /**
     * Get the user who made this booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the showtime for this booking.
     */
    public function showtime()
    {
        return $this->belongsTo(Showtime::class, 'showtime_id', 'showtime_id');
    }
}
