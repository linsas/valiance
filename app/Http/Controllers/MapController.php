<?php

namespace App\Http\Controllers;

use App\Http\Resources\MapResourceCollection;
use App\Models\Map;

class MapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function maps()
    {
        $list = Map::all();
        return MapResourceCollection::response($list);
    }
}
