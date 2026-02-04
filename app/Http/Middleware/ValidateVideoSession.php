<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SecurityService;

class ValidateVideoSession
{
    protected $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token');

        if (!$token) {
            abort(403, 'Session token required');
        }

        if (!$this->securityService->validateSession($token, $request)) {
            abort(403, 'Invalid or expired session');
        }

        return $next($request);
    }
}