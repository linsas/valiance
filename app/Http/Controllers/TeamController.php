<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\TeamService;
use App\Http\Resources\TeamResourceCollection;
use App\Models\Team;

class TeamController extends Controller
{
    protected TeamService $service;

    public function __construct(TeamService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(): JsonResponse
    {
        $list = Team::all();
        return response()->json(['data' => new TeamResourceCollection($list)]);
    }

    public function store(Request $request): Response
    {
        $inputData = $request->json()->all();
        $this->service->store($inputData);
        return response()->noContent();
    }

    public function show(int $id): JsonResponse
    {
        $team = Team::with('tournamentTeams.tournament')->findOrFail($id);

        $players = $this->service->getPlayers($team);
        $history = $this->service->getRelevantHistory($team);

        return response()->json([
            'data' => [
                'id' => $team->id,
                'name' => $team->name,
                'players' => $players->map(fn ($player) => [
                    'id' => $player->id,
                    'alias' => $player->alias,
                ]),
                'transfers' => $history->sortBy('date')->reverse()->values()->toArray(),
                'participations' => $team->tournamentTeams->map(fn ($participant) => [
                    'name' => $participant->name,
                    'tournament' => [
                        'id' => $participant->tournament->id,
                        'name' => $participant->tournament->name,
                    ],
                ])->toArray(),
            ]
        ]);
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
