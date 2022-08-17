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
        $entry = $this->service->findOrFail($id);
        return new TeamResource($entry);
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
