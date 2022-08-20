<?php

use Illuminate\Support\Facades\Route;

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

Route::apiResource('players', 'PlayerController');
Route::apiResource('teams', 'TeamController');
Route::apiResource('tournaments', 'TournamentController');

Route::put('tournaments/{tournament}/teams', 'ParticipationController@reorder');
Route::post('tournaments/{tournament}/advance', 'CompetitionController@advance');

Route::get('matchups', 'MatchupController@index');
Route::get('matchups/{matchup}', 'MatchupController@show');

Route::post('login', 'AuthController@login');
