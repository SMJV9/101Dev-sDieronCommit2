<?php

/**
 * ========================================================
 * EJEMPLOS DE USO - Base de Datos del Juego
 * ========================================================
 * 
 * Este archivo muestra ejemplos prácticos de cómo usar
 * los modelos de Eloquent en tu aplicación.
 * 
 * NO ejecutes este archivo directamente.
 * Copia y pega los ejemplos que necesites en tus
 * controladores o usa `php artisan tinker` para probarlos.
 */

// ========================================================
// EJEMPLO 1: Crear una nueva partida
// ========================================================

use App\Models\Game;
use App\Models\Question;
use App\Models\Round;
use App\Models\RoundAnswer;
use App\Models\GameEvent;

// Crear una nueva partida
$game = Game::create([
    'name' => 'Partido del Viernes Noche',
    'team1_name' => 'Los Debuggers',
    'team2_name' => 'Los Compiladores',
    'status' => 'in_progress',
    'started_at' => now(),
]);

// Log del inicio
GameEvent::logEvent($game->id, 'game_started');

echo "✅ Partida creada: {$game->name}\n";
echo "   Equipo 1: {$game->team1_name}\n";
echo "   Equipo 2: {$game->team2_name}\n\n";


// ========================================================
// EJEMPLO 2: Iniciar una ronda
// ========================================================

// Obtener una pregunta aleatoria que esté activa
$question = Question::where('is_active', true)
    ->inRandomOrder()
    ->first();

// Incrementar el contador de usos
$question->incrementUsage();

// Crear la ronda
$round = Round::create([
    'game_id' => $game->id,
    'question_id' => $question->id,
    'round_number' => 1,
    'multiplier' => 1, // x1 normal, x2 doble, x3 triple
    'active_team' => $game->team1_name,
    'status' => 'in_progress',
    'started_at' => now(),
]);

// Crear el tracking de cada respuesta
foreach ($question->answers()->orderBy('order')->get() as $answer) {
    RoundAnswer::create([
        'round_id' => $round->id,
        'answer_id' => $answer->id,
    ]);
}

// Log del evento
GameEvent::logEvent(
    $game->id, 
    'round_started', 
    $round->id, 
    null,
    ['question' => $question->name, 'round_number' => 1]
);

echo "✅ Ronda #{$round->round_number} iniciada\n";
echo "   Pregunta: {$question->question_text}\n";
echo "   Multiplicador: x{$round->multiplier}\n";
echo "   Turno de: {$round->active_team}\n\n";


// ========================================================
// EJEMPLO 3: Revelar una respuesta (sin acertar)
// ========================================================

// Obtener la primera respuesta no revelada
$roundAnswer = $round->roundAnswers()
    ->where('is_revealed', false)
    ->first();

// Revelarla sin marcarla como correcta (solo mostrar)
$roundAnswer->reveal($round->active_team);

echo "👁️ Respuesta revelada: {$roundAnswer->answer->answer_text}\n";
echo "   Puntos: {$roundAnswer->answer->points}\n";
echo "   Revelada por: {$roundAnswer->revealed_by_team}\n\n";


// ========================================================
// EJEMPLO 4: Marcar una respuesta como acertada
// ========================================================

// Cuando un equipo acierta la respuesta
$roundAnswer2 = $round->roundAnswers()
    ->where('is_revealed', false)
    ->skip(1)
    ->first();

$roundAnswer2->markAsCorrect($round->active_team);

// Log del evento
GameEvent::logEvent(
    $game->id,
    'answer_revealed',
    $round->id,
    $round->active_team,
    [
        'answer' => $roundAnswer2->answer->answer_text,
        'points' => $roundAnswer2->answer->points,
    ]
);

echo "✅ ¡Respuesta acertada!\n";
echo "   Respuesta: {$roundAnswer2->answer->answer_text}\n";
echo "   Puntos: {$roundAnswer2->answer->points}\n";
echo "   Equipo: {$round->active_team}\n\n";


// ========================================================
// EJEMPLO 5: Añadir un strike (X)
// ========================================================

// Cuando un equipo falla
$round->addStrike();

GameEvent::logEvent(
    $game->id,
    'strike_added',
    $round->id,
    $round->active_team
);

echo "❌ Strike añadido! Total: {$round->strikes}/3\n";

// Si llega a 3, el status cambia automáticamente a 'steal_attempt'
if ($round->strikes >= 3) {
    echo "⚠️ 3 STRIKES! Oportunidad de robo de puntos\n\n";
}


// ========================================================
// EJEMPLO 6: Calcular puntos acumulados
// ========================================================

// Obtener el total de puntos acumulados en la ronda
$basePoints = $round->calculateAccumulatedPoints();
$finalPoints = $basePoints * $round->multiplier;

echo "💰 Puntos en la ronda:\n";
echo "   Base: {$basePoints}\n";
echo "   Multiplicador: x{$round->multiplier}\n";
echo "   TOTAL: {$finalPoints}\n\n";


// ========================================================
// EJEMPLO 7: Robo de puntos exitoso
// ========================================================

// El otro equipo intenta robar
$stealingTeam = $game->team2_name;

// Log del intento
GameEvent::logEvent(
    $game->id,
    'steal_attempt',
    $round->id,
    $stealingTeam
);

// Si aciertan una respuesta durante el robo
$stealAnswer = $round->roundAnswers()
    ->where('is_revealed', false)
    ->first();

if ($stealAnswer) {
    $stealAnswer->markAsCorrect($stealingTeam);
    
    // Calcular puntos y asignar al equipo que robó
    $stolenPoints = $round->calculateAccumulatedPoints() * $round->multiplier;
    
    // Actualizar la ronda
    $round->update([
        'winning_team' => $stealingTeam,
        'points_awarded' => $stolenPoints,
        'status' => 'completed',
        'completed_at' => now(),
    ]);
    
    // Asignar puntos al equipo
    if ($stealingTeam === $game->team1_name) {
        $game->increment('team1_score', $stolenPoints);
    } else {
        $game->increment('team2_score', $stolenPoints);
    }
    
    // Log del robo exitoso
    GameEvent::logEvent(
        $game->id,
        'steal_success',
        $round->id,
        $stealingTeam,
        ['points' => $stolenPoints]
    );
    
    echo "🎯 ¡ROBO EXITOSO!\n";
    echo "   Equipo: {$stealingTeam}\n";
    echo "   Puntos ganados: {$stolenPoints}\n\n";
}


// ========================================================
// EJEMPLO 8: Finalizar ronda normalmente
// ========================================================

// Cuando se revelan todas las respuestas
$totalPoints = $round->calculateAccumulatedPoints() * $round->multiplier;

$round->update([
    'winning_team' => $round->active_team,
    'points_awarded' => $totalPoints,
    'status' => 'completed',
    'completed_at' => now(),
]);

// Asignar puntos al equipo ganador
if ($round->winning_team === $game->team1_name) {
    $game->increment('team1_score', $totalPoints);
} else {
    $game->increment('team2_score', $totalPoints);
}

// Log
GameEvent::logEvent(
    $game->id,
    'round_finished',
    $round->id,
    $round->winning_team,
    ['points' => $totalPoints]
);

GameEvent::logEvent(
    $game->id,
    'points_awarded',
    $round->id,
    $round->winning_team,
    ['points' => $totalPoints]
);

echo "🏁 Ronda completada!\n";
echo "   Ganador: {$round->winning_team}\n";
echo "   Puntos otorgados: {$totalPoints}\n\n";


// ========================================================
// EJEMPLO 9: Consultar el estado del juego
// ========================================================

// Recargar el juego desde la BD
$game->refresh();

echo "📊 MARCADOR ACTUAL:\n";
echo "   {$game->team1_name}: {$game->team1_score} pts\n";
echo "   {$game->team2_name}: {$game->team2_score} pts\n\n";

// Obtener la ronda actual (si existe)
$currentRound = $game->currentRound;
if ($currentRound) {
    echo "🎮 Ronda actual: #{$currentRound->round_number}\n";
    echo "   Estado: {$currentRound->status}\n";
    echo "   Turno: {$currentRound->active_team}\n\n";
}


// ========================================================
// EJEMPLO 10: Historial de eventos
// ========================================================

$events = $game->events()
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

echo "📝 Últimos 5 eventos:\n";
foreach ($events as $event) {
    $time = $event->created_at->format('H:i:s');
    echo "   [{$time}] {$event->event_type}";
    if ($event->team) {
        echo " - {$event->team}";
    }
    echo "\n";
}
echo "\n";


// ========================================================
// EJEMPLO 11: Finalizar la partida
// ========================================================

$game->update([
    'status' => 'finished',
    'finished_at' => now(),
]);

GameEvent::logEvent($game->id, 'game_finished', null, null, [
    'winner' => $game->team1_score > $game->team2_score ? $game->team1_name : $game->team2_name,
    'final_score' => [
        $game->team1_name => $game->team1_score,
        $game->team2_name => $game->team2_score,
    ]
]);

echo "🏆 ¡PARTIDA TERMINADA!\n";
if ($game->team1_score > $game->team2_score) {
    echo "   Ganador: {$game->team1_name} 🎉\n";
} elseif ($game->team2_score > $game->team1_score) {
    echo "   Ganador: {$game->team2_name} 🎉\n";
} else {
    echo "   ¡EMPATE! 🤝\n";
}
echo "   Marcador final: {$game->team1_score} - {$game->team2_score}\n\n";


// ========================================================
// EJEMPLO 12: Estadísticas y consultas útiles
// ========================================================

// Preguntas más usadas
$popularQuestions = Question::where('is_active', true)
    ->orderBy('times_used', 'desc')
    ->take(5)
    ->get(['name', 'times_used']);

echo "📈 Top 5 preguntas más usadas:\n";
foreach ($popularQuestions as $i => $q) {
    echo "   " . ($i + 1) . ". {$q->name} ({$q->times_used} veces)\n";
}
echo "\n";

// Partidas recientes
$recentGames = Game::orderBy('created_at', 'desc')
    ->take(5)
    ->get(['name', 'team1_name', 'team2_name', 'status']);

echo "🎮 Últimas 5 partidas:\n";
foreach ($recentGames as $g) {
    echo "   {$g->name} - {$g->team1_name} vs {$g->team2_name} [{$g->status}]\n";
}
echo "\n";

// Total de eventos por tipo
$eventStats = GameEvent::selectRaw('event_type, COUNT(*) as count')
    ->groupBy('event_type')
    ->orderBy('count', 'desc')
    ->get();

echo "📊 Estadísticas de eventos:\n";
foreach ($eventStats as $stat) {
    echo "   {$stat->event_type}: {$stat->count}\n";
}
echo "\n";


// ========================================================
// EJEMPLO 13: Obtener pregunta con todas sus respuestas
// ========================================================

$questionWithAnswers = Question::with('answers')
    ->where('name', 'Lenguajes de programación')
    ->first();

if ($questionWithAnswers) {
    echo "❓ {$questionWithAnswers->question_text}\n";
    foreach ($questionWithAnswers->answers as $answer) {
        echo "   {$answer->order}. {$answer->answer_text} - {$answer->points} pts\n";
    }
    echo "\n";
}


// ========================================================
// EJEMPLO 14: Estado completo de una ronda
// ========================================================

$roundWithAll = Round::with([
    'question.answers',
    'roundAnswers.answer',
    'game'
])->find($round->id);

echo "🎯 Estado de la Ronda #{$roundWithAll->round_number}:\n";
echo "   Pregunta: {$roundWithAll->question->question_text}\n";
echo "   Multiplicador: x{$roundWithAll->multiplier}\n";
echo "   Strikes: {$roundWithAll->strikes}/3\n";
echo "   Turno: {$roundWithAll->active_team}\n\n";

echo "   Respuestas:\n";
foreach ($roundWithAll->roundAnswers as $ra) {
    $status = $ra->is_revealed ? ($ra->is_correct ? '✅' : '👁️') : '🔒';
    echo "   {$status} {$ra->answer->answer_text} ({$ra->answer->points} pts)";
    if ($ra->revealed_by_team) {
        echo " - por {$ra->revealed_by_team}";
    }
    echo "\n";
}
echo "\n";


// ========================================================
// FIN DE LOS EJEMPLOS
// ========================================================

echo "✨ ¡Todos los ejemplos completados!\n";
echo "📚 Revisa DATABASE.md para más información.\n";
