<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchUser;
use App\Models\SoccerMatches;
use App\Models\Teams;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SoccerMatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soccer = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->thisWeek()->get();

        return response()->json($soccer);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        $request->validate([
            'dayOfMatch' => ['required', 'date'],
            'team_local_id' => ['required', 'exists:' . Teams::class . ',id'],
            'team_visit_id' => ['required', 'exists:' . Teams::class . ',id'],
            'referee_id' => ['required', 'exists:' . User::class . ',id'],
            'team_local_goals' => ['required', 'integer'],
            'team_visit_goals' => ['required', 'integer'],
            'team_local_fouls' => ['required', 'integer'],
            'team_visit_fouls' => ['required', 'integer'],
        ]);

            $referee=User::where('rol_id', 2)->find($request->referee_id);

            if ($referee == null){
                return response()->json([
                    'error' => 'El arbitro designado no puede hacer ese rol.'
                ], 400);
            }

            $dayOfMatch=SoccerMatches::where('dayOfMatch', $request->dayOfMatch)->exists();
            if ($dayOfMatch) {
                return response()->json([
                    'error' => 'El horario de juego ya tiene un partido programado para ese día.'
                ], 400);
            }

            $teamLocalMatches = SoccerMatches::where('team_local_id', $request->team_local_id)
                ->where('dayOfMatch', $request->dayOfMatch)
                ->exists();

            if ($teamLocalMatches) {
                return response()->json([
                    'error' => 'El equipo local ya tiene un partido programado para ese día.'
                ], 400);
            }

            // Verifica si el equipo visitante tiene otro partido programado para el mismo día
            $teamVisitMatches = SoccerMatches::where('team_visit_id', $request->team_visit_id)
                ->where('dayOfMatch', $request->dayOfMatch)
                ->exists();

            if ($teamVisitMatches) {
                return response()->json([
                    'error' => 'El equipo visitante ya tiene un partido programado para ese día.'
                ], 400);
            }

            $soccerMatch = SoccerMatches::create([
                'dayOfMatch' => $request->dayOfMatch,
                'team_local_id' => $request->team_local_id,
                'team_visit_id' => $request->team_visit_id,
                'referee_id' => $request->referee_id,
                'team_local_goals' => $request->team_local_goals,
                'team_visit_goals' => $request->team_visit_goals,
                'team_local_fouls' => $request->team_local_fouls,
                'team_visit_fouls' => $request->team_visit_fouls,
                'started' => false,
            ]);

        return response()->json($soccerMatch);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'status' => 422
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $match = SoccerMatches::with('team_local', 'team_visit', 'referee', 'goals')->find($id);

        return response()->json($match);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function addGoalsTeam(Request $request, $id) {
        try {
            $request->validate([
                'player_id' => ['required', 'exists:' . User::class . ',id'],
                'goals' => ['required', 'integer']
            ]);
        $id=SoccerMatches::find($id);

        $match=MatchUser::create([
            'soccerMatch_id' => $id->id,
            'player_id' => $request->player_id,
            'goals' => $request->goals
        ]);

        return response()->json($match);

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'status' => 422
            ], 422);
        }
    }
}
