<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AuthService;

class Authenticate
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->headers->has('authorization')) {
            throw new AuthorizationException('An authoriztion token must be provided.');
        }
        $authHeader = $request->headers->get('authorization');
        $token = Str::after($authHeader, 'JWT ');

        $this->service->validateToken($token);

        return $next($request);
    }
}
