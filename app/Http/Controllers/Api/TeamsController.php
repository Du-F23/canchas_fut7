<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teams;
use App\Models\TeamsUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        if ($user->rol_id === 3) {
            $team = Teams::where('capitan_id', $user->id)->get();

            return response()->json($team);
        }
        elseif ($user->rol_id !== 3) {
            $teams = Teams::with('capitan', 'players')->get();

            return response()->json($teams);
        }
        $team = Teams::with('capitan', 'players')->get();

        return response()->json($team);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name_team' => ['required', 'string', 'min:3', 'unique:' . Teams::class,],
                'acronym' => ['required', 'string', 'max:4', 'unique:' . Teams::class,],
                'image_team' => ['required', 'file'],
                'capitan_id' => ['required', 'integer', 'exists:' . User::class . ',id'],
            ]);

            if ($request->file('image_team')) {
                $image_team = 'image_team/' .  str_replace(" ", "_", $request->name_team) . '_' . date('Y-m-d') . '_' . $request->file('image_team')->getClientOriginalName();
                $image_team = $request->file('image_team')->storeAs('public', $image_team);
            }

            $team = Teams::create([
                'name_team' => $request->name_team,
                'acronym' => $request->acronym,
                'image_team' => $image_team,
                'capitan_id' => $request->capitan_id
            ]);

            return response()->json($team, 201);
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
    public function show($id): JsonResponse
    {
        $team=Teams::find($id);

        return response()->json($team);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name_team' => ['string', 'min:3', 'unique:' . Teams::class,],
                'acronym' => ['string', 'max:4', 'unique:' . Teams::class,],
                'capitan_id' => ['integer', 'exists:' . User::class . ',id'],
            ]);

            $team=Teams::find($id);
//
//            if ($request->file('image_team')) {
//                $exists = Storage::disk('public')->exists($team->image_team);
//                if ($exists) {
//                    Storage::disk('public')->delete($team->image_team);
//                }
//
//                $image_team = 'image_team/' .  str_replace(" ", "_", $team->name_team) . '_' . date('Y-m-d') . '_' . $request->file('image_team')->getClientOriginalName();
//                $image_team = $request->file('image_team')->storeAs('public', $image_team);
//                $team->image_team = $image_team;
//                $team->save();
//            }

            $team->update(
               $request->all()
            );

            return response()->json($team, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'status' => 422
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $team = Teams::find($id);
        $teamUsers = TeamsUser::where('team_id', $team->id)->get();

        // Elimina los jugadores del equipo
        foreach ($teamUsers as $teamUser) {
            $teamUser->delete();
        }

        // Elimina el equipo
        $team->delete();


        return response()->noContent();
    }

    public function addPlayerOfTeam(Request $request, $id) {
        $team = Teams::find($id);

        $playerIds = explode(',', $request->player_id);

        foreach ($playerIds as $playerId) {
            try {
                // Verifica si el jugador ya pertenece a otro equipo
                if (!$this->playerIsUnique($playerId)) {
                    continue; // Salta al siguiente jugador si no es único
                }

                $team->players()->attach($playerId);
            } catch (\Exception $e) {
                // Muestra un mensaje de error al usuario
                return response()->json([
                    "error" => $e->getMessage(),
                ], 400);
            }
        }

        $team = Teams::with('players')->find($id);

        return response()->json(
            $team
        );
    }

    private function playerIsUnique($playerId)
    {
        $playerIsUnique = true; // Asumir que el jugador es único

        // Realiza la consulta para verificar si el jugador ya pertenece a otro equipo
        $teamsUsers = TeamsUser::where('user_id', $playerId)->get();

        if ($teamsUsers->count() > 0) {
            // El jugador ya pertenece a otro equipo
            throw new \Exception("El jugador ya pertenece a otro equipo");
        }

        return $playerIsUnique;
    }

}
