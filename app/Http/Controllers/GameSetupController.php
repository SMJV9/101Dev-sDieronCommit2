<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameSetupController extends Controller
{
    /**
     * Mostrar todas las partidas
     */
    public function index()
    {
        $games = Game::with(['questions' => function($query) {
            $query->orderBy('pivot_round_number')->orderBy('pivot_order_in_round');
        }])->orderBy('created_at', 'desc')->get();

        return response()->json($games);
    }

    /**
     * Crear una nueva partida con preguntas
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:games',
            'round_questions' => 'required|array|size:3',
            'round_questions.*' => 'exists:questions,id',
            'fast_money_questions' => 'required|array|size:5',
            'fast_money_questions.*' => 'exists:questions,id',
        ], [
            'round_questions.size' => 'Debe seleccionar exactamente 3 preguntas para las rondas normales',
            'fast_money_questions.size' => 'Debe seleccionar exactamente 5 preguntas para el dinero rápido',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Verificar que las preguntas de ronda sean del tipo correcto
        $roundQuestions = Question::whereIn('id', $request->round_questions)
                                 ->where('question_type', 'round')
                                 ->get();
        
        if ($roundQuestions->count() !== 3) {
            return response()->json(['error' => 'Todas las preguntas de ronda deben ser del tipo "round"'], 422);
        }

        // Verificar que las preguntas de dinero rápido sean del tipo correcto
        $fastMoneyQuestions = Question::whereIn('id', $request->fast_money_questions)
                                    ->where('question_type', 'fast_money')
                                    ->get();
        
        if ($fastMoneyQuestions->count() !== 5) {
            return response()->json(['error' => 'Todas las preguntas de dinero rápido deben ser del tipo "fast_money"'], 422);
        }

        // Crear la partida
        $game = Game::create([
            'name' => $request->name,
            'team1_name' => $request->team1_name ?? 'Equipo 1',
            'team2_name' => $request->team2_name ?? 'Equipo 2',
            'status' => 'waiting',
        ]);

        // Asociar preguntas de ronda (3 rondas)
        foreach ($request->round_questions as $index => $questionId) {
            $game->questions()->attach($questionId, [
                'question_type' => 'round',
                'round_number' => $index + 1,
                'order_in_round' => 1,
            ]);
        }

        // Asociar preguntas de dinero rápido
        foreach ($request->fast_money_questions as $index => $questionId) {
            $game->questions()->attach($questionId, [
                'question_type' => 'fast_money',
                'round_number' => null,
                'order_in_round' => $index + 1,
            ]);
        }

        // Cargar las relaciones
        $game->load(['questions' => function($query) {
            $query->orderBy('pivot_round_number')->orderBy('pivot_order_in_round');
        }]);

        return response()->json([
            'success' => true,
            'message' => 'Partida creada exitosamente',
            'game' => $game
        ], 201);
    }

    /**
     * Mostrar una partida específica con sus preguntas
     */
    public function show($id)
    {
        $game = Game::with(['questions' => function($query) {
            $query->with('answers')->orderBy('pivot_round_number')->orderBy('pivot_order_in_round');
        }])->find($id);

        if (!$game) {
            return response()->json(['error' => 'Partida no encontrada'], 404);
        }

        return response()->json($game);
    }

    /**
     * Obtener preguntas disponibles por tipo
     */
    public function getQuestionsByType($type = null)
    {
        $query = Question::with('answers')->where('is_active', true);
        
        if ($type && in_array($type, ['round', 'fast_money'])) {
            $query->where('question_type', $type);
        }
        
        $questions = $query->orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'questions' => $questions,
            'type' => $type
        ]);
    }

    /**
     * Cargar partida completa al controlador del juego
     */
    public function loadToGame($id)
    {
        $game = Game::with(['questions' => function($query) {
            $query->with('answers')->orderBy('pivot_round_number')->orderBy('pivot_order_in_round');
        }])->find($id);

        if (!$game) {
            return response()->json(['error' => 'Partida no encontrada'], 404);
        }

        // Separar preguntas por tipo
        $roundQuestions = $game->questions->where('pivot.question_type', 'round');
        $fastMoneyQuestions = $game->questions->where('pivot.question_type', 'fast_money');

        return response()->json([
            'success' => true,
            'game' => $game,
            'round_questions' => $roundQuestions->values(),
            'fast_money_questions' => $fastMoneyQuestions->values(),
            'message' => "Partida '{$game->name}' cargada exitosamente"
        ]);
    }
}
