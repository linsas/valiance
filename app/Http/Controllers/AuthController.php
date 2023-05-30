<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(Request $request): JsonResponse
    {
        $inputData = $request->json()->all();
        $jwt = $this->service->login($inputData);
        return response()->json(['data' => $jwt]);
    }
}
