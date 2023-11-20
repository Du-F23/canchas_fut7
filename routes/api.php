<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/teams', [TeamsController::class, 'index']);
    Route::post('/teams/crear', [TeamsController::class, 'store']);
    Route::get('/teams/{id}', [TeamsController::class, 'show']);
    Route::patch('/teams/{id}/actualizar', [TeamsController::class, 'update']);
    Route::delete('/teams/{id}/borrar', [TeamsController::class, 'destroy']);

    Route::post('/teams/{id}/addPlayers', [TeamsController::class, 'addPlayerOfTeam']);

    // Rutas Partidos
    Route::get('/soccerMatches', [SoccerMatchesController::class, 'index']);
    Route::post('/soccerMatches/crear', [SoccerMatchesController::class, 'store']);
    Route::get('/soccerMatches/{id}', [SoccerMatchesController::class, 'show']);
    Route::patch('/soccerMatches/{id}/actualizar', [SoccerMatchesController::class, 'update']);
    Route::delete('/soccerMatches/{id}/borrar', [SoccerMatchesController::class, 'delete']);

    Route::post('/soccerMatches/{id}/goals', [SoccerMatchesController::class, 'addGoalsTeam']);
});




