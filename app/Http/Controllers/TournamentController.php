<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TournamentService;
use App\Http\Resources\TournamentResource;
use App\Http\Resources\TournamentResourceCollection;

class TournamentController extends Controller
{
    protected $service;

    public function __construct(TournamentService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $list = $this->service->index();
        return new TournamentResourceCollection($list);
    }

    public function store(Request $request)
    {
        $inputData = $request->json()->all();
        $this->service->store($inputData);
        return response()->noContent();
    }

    public function show($id)
    {
        $entry = $this->service->findOrFail($id);
        return new TournamentResource($entry);
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
