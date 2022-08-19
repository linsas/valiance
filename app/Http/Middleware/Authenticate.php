<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use App\Services\AuthService;

class Authenticate
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function handle($request, Closure $next)
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
