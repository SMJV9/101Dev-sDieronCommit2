<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoundAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'answer_id',
        'is_revealed',
        'is_correct',
        'revealed_by_team',
        'revealed_at',
    ];

    protected $casts = [
        'is_revealed' => 'boolean',
        'is_correct' => 'boolean',
        'revealed_at' => 'datetime',
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    /**
     * Revela la respuesta
     */
    public function reveal($team = null)
    {
        $this->update([
            'is_revealed' => true,
            'revealed_by_team' => $team,
            'revealed_at' => now(),
        ]);
    }

    /**
     * Marca la respuesta como correcta
     */
    public function markAsCorrect($team = null)
    {
        $this->update([
            'is_revealed' => true,
            'is_correct' => true,
            'revealed_by_team' => $team,
            'revealed_at' => now(),
        ]);
    }
}
