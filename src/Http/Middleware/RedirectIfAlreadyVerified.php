<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Sharqlabs\LaravelAccessGuard\Services\AccessGuardService;

class RedirectIfAlreadyVerified
{
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();

        $session = Session::driver(config('access-guard.session_driver'));
        $session->start();

        $sessionToken = $session->get('session_token');

        $browser = $request->header('User-Agent');

        // Redirect if the IP is whitelisted or session token is valid
        if (AccessGuardService::validateSessionToken($sessionToken, $browser)) {
            return redirect()->intended($session->get('url_intended', '/'));
        }

        // Proceed if not verified
        return $next($request);
    }
}
