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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre corto/identificador
            $table->text('question_text'); // La pregunta completa
            $table->string('category')->nullable(); // Categoría (dev, general, etc.)
            $table->boolean('is_active')->default(true); // Si está disponible para usar
            $table->integer('times_used')->default(0); // Contador de veces usada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
