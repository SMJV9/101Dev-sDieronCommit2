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
        Schema::create('game_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('round_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('event_type', [
                'game_started',
                'game_finished',
                'round_started',
                'round_finished',
                'answer_revealed',
                'strike_added',
                'steal_attempt',
                'steal_success',
                'steal_failed',
                'points_awarded'
            ]);
            $table->string('team')->nullable(); // Equipo involucrado en el evento
            $table->json('event_data')->nullable(); // Datos adicionales del evento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_events');
    }
};
