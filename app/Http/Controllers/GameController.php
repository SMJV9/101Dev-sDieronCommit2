<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function board()
    {
        return view('board');
    }

    public function controller()
    {
        return view('controller');
    }

    // optional server-side allocation endpoint for persistence
    public function allocate(Request $request)
    {
        $team = $request->input('team');
        $points = (int) $request->input('points', 0);
        if (!$team) {
            return response()->json(['error' => 'team required'], 422);
        }
        $scores = session()->get('team_scores', []);
        if (!isset($scores[$team])) $scores[$team] = 0;
        $scores[$team] += $points;
        session()->put('team_scores', $scores);
        return response()->json(['ok' => true, 'scores' => $scores]);
    }
}
