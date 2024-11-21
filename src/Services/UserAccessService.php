<?php

namespace Sharqlabs\LaravelAccessGuard\Services;

use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;

class UserAccessService
{
    public function handleAccessRequest(string $email, string $clientIp, string $browser): string
    {
        $record = $this->createOrUpdateUserRecord($email);
        $userAccessBrowser = $this->createOrUpdateBrowserSession($record, $clientIp, $browser);
        $this->generateAndSendOtp($record, $userAccessBrowser);

        return $userAccessBrowser->session_token;
    }

    protected function createOrUpdateUserRecord(string $email): UserAccessRecord
    {
        return UserAccessRecord::updateOrCreate(
            ['email' => $email],
            ['domain' => AccessGuardService::getDomainFromEmail($email)]
        );
    }

    protected function createOrUpdateBrowserSession(UserAccessRecord $record, string $clientIp, string $browser): UserAccessBrowser
    {
        $token = bin2hex(random_bytes(32));

        return $record->browsers()->updateOrCreate(
            ['browser' => $browser],
            [
                'session_ip' => $clientIp,
                'session_token' => $token,
                'otp_expires_at' => now()->addMinutes(config('access-guard.otp_expires_in_minutes', 10)),
                'otp_generated_at' => now(),
            ]
        );
    }

    protected function generateAndSendOtp(UserAccessRecord $record, UserAccessBrowser $userAccessBrowser): void
    {
        $otp = random_int(100000, 999999);

        $userAccessBrowser->update(['otp' => $otp, 'otp_generated_at' => now()]);
        $record->notify(new OTPNotification($otp));
    }
}
