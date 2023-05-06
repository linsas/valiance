<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Competition\MatchupService;
use App\Http\Resources\MatchupResource;
use App\Http\Resources\MatchupResourceCollection;
use App\Models\Matchup;

class MatchupController extends Controller
{
    protected $service;

    public function __construct(MatchupService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $list = $this->service->index();
        return new MatchupResourceCollection($list);
    }

    public function show($id)
    {
        $entry = Matchup::findOrFail($id);
        return new MatchupResource($entry);
    }

    public function updateMaps(Request $request, $id)
    {
        $inputData = $request->json()->all();
        $this->service->updateMaps($inputData, $id);
        return response()->noContent();
    }

    public function updateScore(Request $request, $matchupId, $gameNumber)
    {
        $inputData = $request->json()->all();
        $this->service->updateScore($inputData, $matchupId, $gameNumber);
        return response()->noContent();
    }
}
