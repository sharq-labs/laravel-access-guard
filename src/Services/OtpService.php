<?php

namespace Sharqlabs\LaravelAccessGuard\Services;

use Illuminate\Support\Facades\Session;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;
use Exception;

class OtpService
{
    public function verifyOtp(string $sessionToken, string $otp, string $clientIp): \Illuminate\Http\RedirectResponse
    {
        $userAccessBrowser = $this->findBrowserSession($sessionToken, $clientIp);

        if (!$userAccessBrowser || $this->isOtpInvalid($userAccessBrowser, $otp)) {
            AccessGuardService::sendErrorNotification($userAccessBrowser);

            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $this->markSessionAsVerified($userAccessBrowser);
        $this->storeSessionToken($userAccessBrowser);
    }

    protected function findBrowserSession(string $sessionToken, string $clientIp): ?UserAccessBrowser
    {
        return UserAccessBrowser::where('session_token', $sessionToken)
            ->where('session_ip', $clientIp)
            ->first();
    }

    protected function isOtpInvalid(UserAccessBrowser $userAccessBrowser, string $otp): bool
    {
        return $userAccessBrowser->otp != $otp || $userAccessBrowser->otp_expires_at->isPast();
    }

    protected function markSessionAsVerified(UserAccessBrowser $userAccessBrowser): void
    {
        $expiryDate = now()->addDays(config('access-guard.session_token_expiry_days', 7));

        $userAccessBrowser->update([
            'otp' => null,
            'verified_at' => now(),
            'expires_at' => $expiryDate,
        ]);
        AccessGuardService::sendEmailVerificationSuccessNotification($userAccessBrowser);

        $userAccessBrowser->userAccessRecord->update(['last_verified_at' => now()]);
    }

    protected function storeSessionToken(UserAccessBrowser $userAccessBrowser): void
    {
        Session::put('session_token', $userAccessBrowser->session_token);
    }
}
