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
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->integer('round_number'); // Ronda 1, 2, 3, etc.
            $table->integer('multiplier')->default(1); // 1x, 2x, 3x
            $table->integer('strikes')->default(0); // Número de X's
            $table->string('active_team')->nullable(); // Equipo que tiene el turno
            $table->string('winning_team')->nullable(); // Equipo que ganó la ronda
            $table->integer('points_awarded')->default(0); // Puntos asignados en esta ronda
            $table->enum('status', ['in_progress', 'steal_attempt', 'completed'])->default('in_progress');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rounds');
    }
};
