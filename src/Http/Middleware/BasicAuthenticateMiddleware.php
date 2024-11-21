<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $username = config('access-guard.auth.username');
        $password = config('access-guard.auth.password');

        // Get credentials from the request
        $requestUsername = $request->getUser();
        $requestPassword = $request->getPassword();

        // Check credentials
        if ($requestUsername !== $username || $requestPassword !== $password) {
            return response('Unauthorized.', 401, [
                'WWW-Authenticate' => 'Basic realm="Access Denied"',
            ]);
        }

        return $next($request);
    }
}

