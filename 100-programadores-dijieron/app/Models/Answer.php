<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer_text',
        'points',
        'order',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function roundAnswers()
    {
        return $this->hasMany(RoundAnswer::class);
    }
}
