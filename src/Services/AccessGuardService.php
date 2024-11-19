<?php

namespace Sharqlabs\LaravelAccessGuard\Services;

use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Illuminate\Support\Facades\Request;

class AccessGuardService
{
    /**
     * Check if the given IP is whitelisted.
     *
     * @param string $clientIp
     * @return bool
     */
    public static function isIpWhitelisted(string $clientIp): bool
    {
        return UserAccessRecord::query()
            ->where('is_whitelisted', true)
            ->where('primary_ip', $clientIp)
            ->exists();
    }

    /**
     * Validate a session token for the given browser.
     *
     * @param string|null $sessionToken
     * @param string $browser
     * @return bool
     */
    public static function validateSessionToken(?string $sessionToken, string $browser): bool
    {
        if (!$sessionToken) {
            return false;
        }

        $browserSession = UserAccessBrowser::query()
            ->where('session_token', $sessionToken)
            ->first();

        if (!$browserSession || $browserSession->isExpired()) {
            return false;
        }

        return $browserSession->browser === $browser;
    }

    /**
     * Get the current URL without the subdomain.
     *
     * @return string
     */
    public static function getCurrentUrlWithoutSubdomain(): string
    {
        $url = config('app.url', Request::fullUrl());

        $parsedUrl = parse_url($url);

        $host = $parsedUrl['host'] ?? '';
        $hostParts = explode('.', $host);

        // Remove the subdomain if it exists (assuming at least 2 parts like domain and TLD)
        if (count($hostParts) > 2) {
            $host = implode('.', array_slice($hostParts, -2));
        }

        $scheme = $parsedUrl['scheme'] ?? 'http';
        $path = $parsedUrl['path'] ?? '';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

        return $scheme . '://' . $host . $path . $query;
    }
}
