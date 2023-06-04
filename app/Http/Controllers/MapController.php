<?php

namespace App\Http\Controllers;

use App\Http\Resources\MapResourceCollection;
use App\Models\Map;
use Illuminate\Http\JsonResponse;

class MapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function maps(): JsonResponse
    {
        $list = Map::all();
        return MapResourceCollection::response($list);
    }
}
