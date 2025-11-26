<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'team1_name',
        'team2_name',
        'team1_score',
        'team2_score',
        'status',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function events()
    {
        return $this->hasMany(GameEvent::class);
    }

    public function currentRound()
    {
        return $this->hasOne(Round::class)->where('status', 'in_progress')->latest();
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'game_questions')
                    ->withPivot('question_type', 'round_number', 'order_in_round')
                    ->withTimestamps();
    }

    public function roundQuestions()
    {
        return $this->questions()->wherePivot('question_type', 'round')
                    ->orderBy('pivot_round_number')
                    ->orderBy('pivot_order_in_round');
    }

    public function fastMoneyQuestions()
    {
        return $this->questions()->wherePivot('question_type', 'fast_money')
                    ->orderBy('pivot_order_in_round');
    }
}
