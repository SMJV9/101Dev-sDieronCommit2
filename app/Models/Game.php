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
}
