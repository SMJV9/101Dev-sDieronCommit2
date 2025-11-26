<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->enum('question_type', ['round', 'fast_money'])->default('round');
            $table->integer('round_number')->nullable(); // 1, 2, 3 para rondas normales
            $table->integer('order_in_round')->default(1); // orden dentro de la ronda/partida
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['game_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_questions');
    }
};
