<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $primaryKey = 'game_id';

    protected $fillable = [
        'title',
        'description',
        'game_type',
    ];

    /**
     * Get the questions belonging to this game.
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'game_id', 'game_id');
    }

    /**
     * Get the game rounds played for this game.
     */
    public function gameRounds()
    {
        return $this->hasMany(GameRound::class, 'game_id', 'game_id');
    }
}
