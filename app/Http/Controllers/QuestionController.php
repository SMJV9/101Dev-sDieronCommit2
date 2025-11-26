<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Mostrar todas las preguntas
     */
    public function index()
    {
        $questions = Question::with('answers')->orderBy('created_at', 'desc')->get();
        return response()->json($questions);
    }

    /**
     * Crear una nueva pregunta
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:questions',
            'question_text' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'question_type' => 'required|in:round,fast_money',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.points' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Crear la pregunta
        $question = Question::create([
            'name' => $request->name,
            'question_text' => $request->question_text,
            'description' => $request->description,
            'category' => $request->category ?? 'general',
            'question_type' => $request->question_type,
            'is_active' => true,
        ]);

        // Ordenar respuestas por puntos de mayor a menor
        $sortedAnswers = collect($request->answers)->sortByDesc('points')->values();

        // Crear las respuestas
        foreach ($sortedAnswers as $index => $answerData) {
            Answer::create([
                'question_id' => $question->id,
                'answer_text' => $answerData['text'],
                'points' => $answerData['points'],
                'order' => $index + 1,
            ]);
        }

        // Cargar las respuestas en la pregunta
        $question->load('answers');

        return response()->json([
            'success' => true,
            'message' => 'Pregunta creada exitosamente',
            'question' => $question
        ], 201);
    }

    /**
     * Mostrar una pregunta específica
     */
    public function show($id)
    {
        $question = Question::with('answers')->find($id);
        
        if (!$question) {
            return response()->json(['error' => 'Pregunta no encontrada'], 404);
        }

        return response()->json($question);
    }

    /**
     * Actualizar una pregunta
     */
    public function update(Request $request, $id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['error' => 'Pregunta no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:questions,name,' . $id,
            'question_text' => 'required|string',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'question_type' => 'required|in:round,fast_money',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.points' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Actualizar la pregunta
        $question->update([
            'name' => $request->name,
            'question_text' => $request->question_text,
            'description' => $request->description,
            'category' => $request->category ?? 'general',
            'question_type' => $request->question_type,
        ]);

        // Eliminar respuestas anteriores
        $question->answers()->delete();

        // Ordenar respuestas por puntos de mayor a menor
        $sortedAnswers = collect($request->answers)->sortByDesc('points')->values();

        // Crear las nuevas respuestas
        foreach ($sortedAnswers as $index => $answerData) {
            Answer::create([
                'question_id' => $question->id,
                'answer_text' => $answerData['text'],
                'points' => $answerData['points'],
                'order' => $index + 1,
            ]);
        }

        // Recargar las respuestas
        $question->load('answers');

        return response()->json([
            'success' => true,
            'message' => 'Pregunta actualizada exitosamente',
            'question' => $question
        ]);
    }

    /**
     * Eliminar una pregunta
     */
    public function destroy($id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['error' => 'Pregunta no encontrada'], 404);
        }

        $questionName = $question->name;
        $question->delete(); // Las respuestas se eliminan automáticamente por cascade

        return response()->json([
            'success' => true,
            'message' => "Pregunta '{$questionName}' eliminada exitosamente"
        ]);
    }

    /**
     * Activar/desactivar una pregunta
     */
    public function toggleActive($id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['error' => 'Pregunta no encontrada'], 404);
        }

        $question->is_active = !$question->is_active;
        $question->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado',
            'is_active' => $question->is_active
        ]);
    }

    /**
     * Cargar pregunta en el controller
     */
    public function loadToController($id)
    {
        $question = Question::with('answers')->find($id);

        if (!$question) {
            return response()->json(['error' => 'Pregunta no encontrada'], 404);
        }

        // Incrementar contador de uso
        $question->incrementUsage();

        return response()->json([
            'success' => true,
            'question' => $question->question_text,
            'answers' => $question->answers->map(function($answer) {
                return [
                    'text' => $answer->answer_text,
                    'count' => $answer->points,
                    'revealed' => false,
                    'correct' => false,
                ];
            })->toArray()
        ]);
    }
}
