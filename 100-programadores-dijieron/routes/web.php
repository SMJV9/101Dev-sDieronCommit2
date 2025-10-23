<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('home');
});

Route::get('/tablero', [GameController::class, 'board'])->name('board');
Route::get('/controller', [GameController::class, 'controller'])->name('controller');
Route::get('/questions', function () {
    return view('questions');
})->name('questions');
Route::post('/round/allocate', [GameController::class, 'allocate'])->name('round.allocate');

