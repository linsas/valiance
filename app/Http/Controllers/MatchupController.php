<?php

namespace App\Http\Controllers;

use App\Services\Competition\MatchupService;
use App\Http\Resources\MatchupResource;
use App\Http\Resources\MatchupResourceCollection;

class MatchupController extends Controller
{
    protected $service;

    public function __construct(MatchupService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $list = $this->service->index();
        return new MatchupResourceCollection($list);
    }

    public function show($id)
    {
        $entry = $this->service->findOrFail($id);
        return new MatchupResource($entry);
    }
}
