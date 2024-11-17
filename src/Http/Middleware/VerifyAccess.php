<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;

class VerifyAccess
{
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();
        $sessionToken = $request->cookie('session_token'); // Retrieve token from cookie

        // Check access by IP only
        if ($this->hasAccessByIp($clientIp)) {
            return $next($request);
        }

        // Check access by email and browser session
        if ($this->validateSessionToken($sessionToken, $clientIp, $request)) {
            return $next($request);
        }

        // Redirect to the access verification form
        return redirect()->route('laravel-access-guard.form')->with('error', 'Access denied. Please verify.');
    }

    /**
     * Check access by email, IP, and browser session.
     */
    protected function validateSessionToken(?string $sessionToken, string $clientIp, Request $request): bool
    {
        if (!$sessionToken) {
            return false;
        }

        // Find the browser session by token
        $browserSession = UserAccessBrowser::query()->where('session_token', $sessionToken)->first();

        if (!$browserSession || $browserSession->isExpired()) {
            return false;
        }

        //additional check Ensure the browser matches
        $browser = $request->header('User-Agent');

        return $browserSession->browser === $browser;
    }

    /**
     * Check access by primary IP only.
     */
    protected function hasAccessByIp(string $clientIp): bool
    {
        $record = UserAccessRecord::query()->where('primary_ip', $clientIp)->first();

        return $record && $record->is_whitelisted;
    }
}
