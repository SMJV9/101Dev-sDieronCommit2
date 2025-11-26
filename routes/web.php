<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\QuestionController;

Route::get('/', function () {
    return view('home');
});

Route::get('/tablero', [GameController::class, 'board'])->name('board');
Route::get('/controller', [GameController::class, 'controller'])->name('controller');
Route::get('/questions', function () {
    return view('questions');
})->name('questions');
// Ruta para Juego Rápido (página completa)
Route::get('/quick-game', function(){ return view('quickgame'); })->name('quickgame');
Route::get('/quickgame-control', function(){ return view('quickgame_control'); })->name('quickgame.control');
Route::get('/quickgame-display', function(){ return view('quickgame_display'); })->name('quickgame.display');
Route::post('/round/allocate', [GameController::class, 'allocate'])->name('round.allocate');

// Rutas para gestión de partidas
Route::get('/games', function () {
    return view('games');
})->name('games');

// API Routes para gestión de preguntas
Route::prefix('api')->group(function () {
    Route::get('/questions', [QuestionController::class, 'index'])->name('api.questions.index');
    Route::post('/questions', [QuestionController::class, 'store'])->name('api.questions.store');
    Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('api.questions.show');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('api.questions.update');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('api.questions.destroy');
    Route::post('/questions/{id}/toggle-active', [QuestionController::class, 'toggleActive'])->name('api.questions.toggle');
    Route::get('/questions/{id}/load', [QuestionController::class, 'loadToController'])->name('api.questions.load');
    
    // API Routes para gestión de partidas
    Route::get('/games', [\App\Http\Controllers\GameSetupController::class, 'index'])->name('api.games.index');
    Route::post('/games', [\App\Http\Controllers\GameSetupController::class, 'store'])->name('api.games.store');
    Route::get('/games/{id}', [\App\Http\Controllers\GameSetupController::class, 'show'])->name('api.games.show');
    Route::get('/games/{id}/load', [\App\Http\Controllers\GameSetupController::class, 'loadToGame'])->name('api.games.load');
    Route::get('/questions/type/{type}', [\App\Http\Controllers\GameSetupController::class, 'getQuestionsByType'])->name('api.questions.bytype');
});

