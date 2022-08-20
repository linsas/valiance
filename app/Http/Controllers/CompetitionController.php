<?php

namespace App\Http\Controllers;

use App\Services\Competition\CompetitionService;

class CompetitionController extends Controller
{
    protected $service;

    public function __construct(CompetitionService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    public function advance($tournament)
    {
        $this->service->advance($tournament);
        return response()->noContent();
    }
}
