<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\TeamService;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamResourceCollection;
use App\Models\Team;

class TeamController extends Controller
{
    private TeamService $service;

    public function __construct(TeamService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(): JsonResponse
    {
        $teams = Team::all();
        return TeamResourceCollection::response($teams);
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
        $history = $this->service->getTransfersHistory($team);

        return TeamResource::response($team, $players, $history);
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
