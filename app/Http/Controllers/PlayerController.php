<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\PlayerService;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\PlayerResourceCollection;
use App\Models\Player;

class PlayerController extends Controller
{
    private PlayerService $service;

    public function __construct(PlayerService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(): JsonResponse
    {
        $players = Player::with('history.team')->get();
        return PlayerResourceCollection::response($players);
    }

    public function store(Request $request): Response
    {
        $inputData = $request->json()->all();
        $this->service->store($inputData);
        return response()->noContent();
    }

    public function show(int $id): JsonResponse
    {
        $player = Player::with(['history.team', 'tournamentTeamPlayers.tournamentTeam.tournament'])->findOrFail($id);
        return PlayerResource::response($player);
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
