<?php

namespace Sharqlabs\LaravelAccessGuard\Services;

use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;

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
}
