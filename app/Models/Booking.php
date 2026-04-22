<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

    /**
     * Mark booking as watched when showtime start + movie duration has passed.
     */
    public function refreshWatchedStatus(bool $save = true): bool
    {
        if (! in_array($this->status, ['confirmed', 'pending', 'upcoming'], true)) {
            return false;
        }

        $showtime = $this->relationLoaded('showtime')
            ? $this->showtime
            : $this->showtime()->with('movie:movie_id,duration')->first();

        if (! $showtime || ! $showtime->show_date) {
            return false;
        }

        $durationMinutes = (int) ($showtime->movie?->duration ?? 0);
        $showTime = $showtime->show_time;

        $timeValue = $showTime instanceof Carbon
            ? $showTime->format('H:i:s')
            : (is_string($showTime) ? substr($showTime, 0, 8) : '00:00:00');

        $endsAt = Carbon::parse($showtime->show_date->format('Y-m-d') . ' ' . $timeValue)
            ->addMinutes(max(0, $durationMinutes));

        if (now()->lt($endsAt)) {
            return false;
        }

        $this->status = 'watched';

        if ($save && $this->exists) {
            $this->save();
        }

        return true;
    }

    /**
     * Sync watched status for a collection of bookings.
     */
    public static function syncWatchedStatuses(Collection $bookings): void
    {
        $bookings->each(function (Booking $booking): void {
            $booking->refreshWatchedStatus();
        });
    }
}
