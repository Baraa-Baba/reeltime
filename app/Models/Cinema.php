<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;

    protected $primaryKey = 'cinema_id';

    protected $fillable = [
        'name',
        'location',
    ];

    /**
     * Get the showtimes at this cinema.
     */
    public function showtimes()
    {
        return $this->hasMany(Showtime::class, 'cinema_id', 'cinema_id');
    }
}
