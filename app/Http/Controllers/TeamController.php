<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TeamService;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamResourceCollection;

class TeamController extends Controller
{
    protected $service;

    public function __construct(TeamService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $list = $this->service->index();
        return new TeamResourceCollection($list);
    }

    public function store(Request $request)
    {
        $inputData = $request->json()->all();
        $this->service->store($inputData);
        return response()->noContent();
    }

    public function show($id)
    {
        $team = $this->service->findOrFail($id);

        $players = $this->service->getPlayers($team);
        $history = $this->service->getRelevantHistory($team);

        return ['data' => [
            'id' => $team->id,
            'name' => $team->name,
            'players' => $players->map(fn ($player) => [
                'id' => $player->id,
                'alias' => $player->alias,
            ]),
            'transfers' => $history->sortBy('date')->reverse()->values()->toArray(),
            'participations' => $team->tournamentTeams->map(fn ($tteam) => [
                'name' => $tteam->name,
                'tournament' => [
                    'id' => $tteam->tournament->id,
                    'name' => $tteam->tournament->name,
                ],
            ])->toArray(),
        ]];
    }

    public function update(Request $request, $id)
    {
        $inputData = $request->json()->all();
        $this->service->update($inputData, $id);
        return response()->noContent();
    }

    public function destroy($id)
    {
        $this->service->destroy($id);
        return response()->noContent();
    }
}
