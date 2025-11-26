<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;

echo "\n";
echo "========================================\n";
echo "  VERIFICACIÓN DE BASE DE DATOS\n";
echo "========================================\n\n";

echo "✅ Total de preguntas: " . Question::count() . "\n";
echo "✅ Total de respuestas: " . \App\Models\Answer::count() . "\n\n";

echo "📋 Ejemplo de pregunta:\n";
echo "----------------------------------------\n";

$question = Question::with('answers')->first();

if ($question) {
    echo "❓ {$question->question_text}\n";
    echo "   Categoría: {$question->category}\n\n";
    
    foreach ($question->answers()->orderBy('order')->get() as $answer) {
        echo "   {$answer->order}. {$answer->answer_text} - {$answer->points} pts\n";
    }
}

echo "\n";
echo "📚 Todas las preguntas disponibles:\n";
echo "----------------------------------------\n";

foreach (Question::all() as $q) {
    echo "• {$q->name} ({$q->category})\n";
}

echo "\n✨ ¡Base de datos lista para usar!\n\n";
