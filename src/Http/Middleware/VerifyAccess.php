<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Sharqlabs\LaravelAccessGuard\Services\AccessGuardService;

class VerifyAccess
{
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();

        $session = Session::driver(config('access-guard.session_driver'));
        $sessionToken = $session->get('session_token');

        $browser = $request->header('User-Agent');

        // Check access by IP or session token
        if (AccessGuardService::validateSessionToken($sessionToken, $browser)) {
            return $next($request);
        }

        // Redirect to the access verification form
        return redirect()->route('laravel-access-guard.form')->with('error', 'Access denied. Please verify.');
    }
}
