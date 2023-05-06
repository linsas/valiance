<?php

namespace App\Http\Controllers;

use App\Services\Competition\CompetitionService;
use Illuminate\Http\Response;

class CompetitionController extends Controller
{
    protected CompetitionService $service;

    public function __construct(CompetitionService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    public function advance(int $tournament): Response
    {
        $this->service->advance($tournament);
        return response()->noContent();
    }
}
