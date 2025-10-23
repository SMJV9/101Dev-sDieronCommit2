<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'round_id',
        'event_type',
        'team',
        'event_data',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    /**
     * Registra un nuevo evento del juego
     */
    public static function logEvent($gameId, $eventType, $roundId = null, $team = null, $data = null)
    {
        return self::create([
            'game_id' => $gameId,
            'round_id' => $roundId,
            'event_type' => $eventType,
            'team' => $team,
            'event_data' => $data,
        ]);
    }
}
