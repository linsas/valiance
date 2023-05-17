<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\TournamentService;
use App\Http\Resources\TournamentResource;
use App\Http\Resources\TournamentResourceCollection;
use App\Models\Tournament;

class TournamentController extends Controller
{
    private TournamentService $service;

    public function __construct(TournamentService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(): JsonResponse
    {
        $tournaments = Tournament::all();
        return TournamentResourceCollection::response($tournaments);
    }

    public function store(Request $request): Response
    {
        $inputData = $request->json()->all();
        $this->service->store($inputData);
        return response()->noContent();
    }

    public function show(int $id): JsonResponse
    {
        $tournament = Tournament::with([
            'tournamentTeams.team',
            'tournamentTeams.tournamentTeamPlayers.player',
            'rounds.matchups.team1',
            'rounds.matchups.team2',
            'rounds.matchups.games'
        ])->findOrFail($id);

        return TournamentResource::response($tournament);
    }

    public function update(Request $request, int $id): Response
    {
        $inputData = $request->json()->all();
        $this->service->update($inputData, $id);
        return response()->noContent();
    }

    public function destroy(int $id): Response
    {
        $this->service->destroy($id);
        return response()->noContent();
    }
}
