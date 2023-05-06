<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ParticipationService;

class ParticipationController extends Controller
{
    protected ParticipationService $service;

    public function __construct(ParticipationService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    public function reorder(Request $request, int $tournament): Response
    {
        $inputData = $request->json()->all();
        $this->service->updateParticipations($inputData, $tournament);
        return response()->noContent();
    }
}
