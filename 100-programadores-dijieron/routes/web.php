<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\GameController;

Route::get('/tablero', [GameController::class, 'board'])->name('board');
Route::get('/controller', [GameController::class, 'controller'])->name('controller');
Route::post('/round/allocate', [GameController::class, 'allocate'])->name('round.allocate');
