<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\MatchupController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('players', PlayerController::class);
Route::apiResource('teams', TeamController::class);
Route::apiResource('tournaments', TournamentController::class);

Route::put('tournaments/{tournament}/teams', [ParticipationController::class, 'reorder']);
Route::post('tournaments/{tournament}/advance', [CompetitionController::class, 'advance']);

Route::get('matchups', [MatchupController::class, 'index']);
Route::get('matchups/{matchup}', [MatchupController::class, 'show']);
Route::put('matchups/{matchup}', [MatchupController::class, 'updateMaps']);
Route::put('matchups/{matchup}/game/{game}', [MatchupController::class, 'updateScore']);

Route::post('login', [AuthController::class, 'login']);

Route::get('{any}', fn () => response()->json(['messge' => 'Not Found'], 404))->where('any', '.*');
