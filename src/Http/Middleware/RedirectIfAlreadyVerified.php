<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sharqlabs\LaravelAccessGuard\Services\AccessGuardService;

class RedirectIfAlreadyVerified
{
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();
        $sessionToken = $request->cookie('session_token'); // Retrieve token from cookies
        $browser = $request->header('User-Agent');

        // Redirect if the IP is whitelisted or session token is valid
        if (AccessGuardService::isIpWhitelisted($clientIp) ||
            AccessGuardService::validateSessionToken($sessionToken, $browser)) {
            return redirect('/');
        }

        // Proceed if not verified
        return $next($request);
    }
}
