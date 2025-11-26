<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'question_id',
        'round_number',
        'multiplier',
        'strikes',
        'active_team',
        'winning_team',
        'points_awarded',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function roundAnswers()
    {
        return $this->hasMany(RoundAnswer::class);
    }

    public function events()
    {
        return $this->hasMany(GameEvent::class);
    }

    /**
     * Calcula el total de puntos acumulados en la ronda
     */
    public function calculateAccumulatedPoints()
    {
        return $this->roundAnswers()
            ->where('is_correct', true)
            ->with('answer')
            ->get()
            ->sum(fn($ra) => $ra->answer->points);
    }

    /**
     * Añade un strike (X) a la ronda
     */
    public function addStrike()
    {
        $this->increment('strikes');
        
        // Si llega a 3, cambiar a steal_attempt
        if ($this->strikes >= 3) {
            $this->update(['status' => 'steal_attempt']);
        }
    }
}
