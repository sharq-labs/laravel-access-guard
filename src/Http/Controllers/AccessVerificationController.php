<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Sharqlabs\LaravelAccessGuard\Services\AccessGuardService;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;
use Sharqlabs\LaravelAccessGuard\Notifications\OTPNotification;
use Carbon\Carbon;
use Sharqlabs\LaravelAccessGuard\Rules\EmailWithDomain;

class AccessVerificationController
{
    /**
     * Show the email verification form.
     */
    public function showForm()
    {
        return view('laravel-access-guard::form');
    }

    /**
     * Show the OTP verification form.
     */
    public function showOtpForm(Request $request)
    {
        $sessionToken = $request->query('session_token'); // Retrieve session token from query

        return view('laravel-access-guard::otp', compact('sessionToken'));
    }

    /**
     * Handle form submission for access verification.
     */
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', new EmailWithDomain()],
        ], [
            'email.exists' => 'The provided email is not registered on this domain',
        ]);

        $email = $validated['email'];
        $clientIp = $request->ip();
        $browser = $request->header('User-Agent');

        $record = $this->createOrUpdateUserRecord($email, $clientIp);
        $browserSession = $this->createOrUpdateBrowserSession($record, $clientIp, $browser);

        $this->generateAndSendOtp($record, $browserSession);

        return redirect()->route('laravel-access-guard.otp-form', ['session_token' => $browserSession->session_token]);
    }

    /**
     * Verify the OTP.
     */
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp' => 'required|numeric',
            'sessionToken' => 'required|string',
        ]);

        $sessionToken = $validated['sessionToken'];
        $clientIp = $request->ip();

        $browserSession = UserAccessBrowser::query()
            ->where('session_token', $sessionToken)
            ->where('session_ip', $clientIp)
            ->first();

        if (!$browserSession || $browserSession->otp != $validated['otp'] || $browserSession->otp_expires_at->isPast()) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $this->markSessionAsVerified($browserSession);

        // Use custom session
        $session = Session::driver(config('access-guard.session_driver'));
        $session->put('session_token', $browserSession->session_token);

        return redirect()->intended('/');
    }

    /**
     * Create or update the user record with the primary IP.
     */
    protected function createOrUpdateUserRecord(string $email, string $primaryIp): UserAccessRecord
    {
        return UserAccessRecord::updateOrCreate(
            ['email' => $email, 'domain' => AccessGuardService::getCurrentUrlWithoutSubdomain()],
            ['primary_ip' => $primaryIp]
        );
    }

    /**
     * Create or update the browser session.
     */
    protected function createOrUpdateBrowserSession(UserAccessRecord $record, string $clientIp, string $browser): UserAccessBrowser
    {
        $token = bin2hex(random_bytes(32)); // Generate secure token

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

    /**
     * Generate and send an OTP to the user.
     */
    protected function generateAndSendOtp(UserAccessRecord $record, UserAccessBrowser $browserSession): void
    {

        $otp = $this->generateOtp();

        $browserSession->update([
            'otp' => $otp,
            'otp_generated_at' => now(),
        ]);

        $record->notify(new OTPNotification($otp));
    }


    /**
     * Mark the browser session and user record as verified.
     */
    protected function markSessionAsVerified(UserAccessBrowser $browserSession): void
    {
        $expiryDate = $this->getTokenExpiryDate();

        $browserSession->update([
            'otp' => null,
            'verified_at' => now(),
            'expires_at' => $expiryDate,
        ]);

        $browserSession->userAccessRecord->update(['last_verified_at' => now()]);
    }

    /**
     * Get the token expiry date based on the configuration.
     */
    protected function getTokenExpiryDate(): Carbon
    {
        return config('access-guard.session_token_expiry', now()->addDays(7));
    }

    /**
     * Generate a random OTP.
     *
     * @return int
     * @throws Exception
     */
    protected function generateOtp(): int
    {
        return random_int(100000, 999999); // Configurable OTP length
    }

}
