<?php

namespace Sharqlabs\LaravelAccessGuard\Services;

use Sharqlabs\LaravelAccessGuard\Notifications\AccessRecordAddedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;
use Illuminate\Support\Facades\Request;
use Sharqlabs\LaravelAccessGuard\Notifications\ErrorNotification;

class AccessGuardService
{

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
     * Handle email verification logic.
     */
    public static function sendEmailVerificationSuccessNotification(UserAccessBrowser $userAccessBrowser): void
    {
        $notificationEmails = explode(',', config('access-guard.notifications.recipient_emails', ''));

        foreach ($notificationEmails as $email) {
            $email = trim($email);
            if (!empty($email)) {
                try {
                    // Notify each email about the new access record
                    Notification::route('mail', $email)->notify(new AccessRecordAddedNotification($userAccessBrowser));
                } catch (\Exception $e) {
                    Log::error('Failed to send Access Record Added Notification: ' . $e->getMessage(), [
                        'email' => $email,
                        'record_id' => $record->id,
                    ]);
                }
            }
        }
    }


    /**
     * Send error notifications.
     *
     * @param UserAccessBrowser $userAccessBrowser
     * @return void
     */
    public static function sendErrorNotification(UserAccessBrowser $userAccessBrowser): void
    {
        // Check if error notifications are enabled
        if (!config('access-guard.notifications.is_errors_notifications_enabled')) {
            return;
        }

        $notificationEmails = explode(',', config('access-guard.notifications.recipient_emails', ''));

        foreach ($notificationEmails as $email) {
            $email = trim($email);
            if (!empty($email)) {
                try {
                    Notification::route('mail', $email)->notify(new ErrorNotification($userAccessBrowser));
                } catch (\Exception $e) {
                    Log::error('Failed to send error notification: ' . $e->getMessage(), [
                        'recipient_email' => $email,
                        'error_details' => $errorDetails,
                    ]);
                }
            }
        }
    }


    public static function getDomainFromEmail($value)
    {
        return substr(strrchr($value, '@'), 1);
    }
}
