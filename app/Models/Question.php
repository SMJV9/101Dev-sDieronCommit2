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
        'category',
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

    /**
     * Incrementa el contador de veces usada
     */
    public function incrementUsage()
    {
        $this->increment('times_used');
    }
}
