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
    protected TournamentService $service;

    public function __construct(TournamentService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(): JsonResponse
    {
        $list = Tournament::all();
        return response()->json(['data' => new TournamentResourceCollection($list)]);
    }

    public function store(Request $request): Response
    {
        $inputData = $request->json()->all();
        $this->service->store($inputData);
        return response()->noContent();
    }

    public function show(int $id): JsonResponse
    {
        $entry = Tournament::findOrFail($id);
        return response()->json(['data' => new TournamentResource($entry)]);
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
