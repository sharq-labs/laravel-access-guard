<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;
use Sharqlabs\LaravelAccessGuard\Services\AccessGuardService;

class VerifySessionTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $clientIp = $request->ip();

        $session = Session::driver(config('access-guard.session_driver'));
        $sessionToken = $request->session_token;

        $browser = $request->header('User-Agent');


        $browserSession = UserAccessBrowser::query()
            ->where('session_token', $sessionToken)
            ->first();

        // Check access by IP or session token
        if ($browserSession && $browserSession->browser === $browser && $request->ip() == $browserSession->session_ip) {
            return $next($request);
        }

        // Redirect to the access verification form
        return redirect()->route('laravel-access-guard.form')->with('error', 'Access denied. Please verify.');
    }
}
