<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\Competition\MatchupService;
use App\Http\Resources\MatchupResource;
use App\Http\Resources\MatchupResourceCollection;
use App\Models\Matchup;

class MatchupController extends Controller
{
    protected MatchupService $service;

    public function __construct(MatchupService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(): JsonResponse
    {
        $list = Matchup::with(['team1', 'team2', 'round.tournament', 'games'])->get();
        return response()->json(['data' => new MatchupResourceCollection($list)]);
    }

    public function show(int $id): JsonResponse
    {
        $entry = Matchup::findOrFail($id);
        return response()->json(['data' => new MatchupResource($entry)]);
    }

    public function updateMaps(Request $request, int $id): Response
    {
        $inputData = $request->json()->all();
        $this->service->updateMaps($inputData, $id);
        return response()->noContent();
    }

    public function updateScore(Request $request, int $matchupId, int $gameNumber): Response
    {
        $inputData = $request->json()->all();
        $this->service->updateScore($inputData, $matchupId, $gameNumber);
        return response()->noContent();
    }
}
