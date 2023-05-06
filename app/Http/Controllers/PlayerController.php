<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlayerService;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\PlayerResourceCollection;
use App\Models\Player;

class PlayerController extends Controller
{
    protected $service;

    public function __construct(PlayerService $service)
    {
        $this->service = $service;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $list = Player::all();
        return new PlayerResourceCollection($list);
    }

    public function store(Request $request)
    {
        $inputData = $request->json()->all();
        $this->service->store($inputData);
        return response()->noContent();
    }

    public function show($id)
    {
        $player = Player::findOrFail($id);
        return new PlayerResource($player);
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
