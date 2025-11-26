<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'question_text',
        'description',
        'category',
        'question_type',
        'is_active',
        'times_used',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_questions')
                    ->withPivot('question_type', 'round_number', 'order_in_round')
                    ->withTimestamps();
    }

    /**
     * Incrementa el contador de veces usada
     */
    public function incrementUsage()
    {
        $this->increment('times_used');
    }

    public function scopeRoundType($query)
    {
        return $query->where('question_type', 'round');
    }

    public function scopeFastMoneyType($query)
    {
        return $query->where('question_type', 'fast_money');
    }
}
