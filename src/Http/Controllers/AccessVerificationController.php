<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Controllers;

use Illuminate\Http\Request;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessBrowser;
use Sharqlabs\LaravelAccessGuard\Notifications\OTPNotification;

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
            'email' => 'required|email|exists:user_access_records,email',
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

        $browserSession = UserAccessBrowser::where('session_token', $sessionToken)
            ->where('session_ip', $clientIp)
            ->first();

        if (!$browserSession || $browserSession->otp != $validated['otp'] || $browserSession->otp_expires_at->isPast()) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $this->markSessionAsVerified($browserSession);

        $tokenExpiry = config('access-guard.session_token_expiry', 60 * 24 * 7); // Default to 7 days
        $cookie = cookie( 'session_token', $browserSession->session_token, $tokenExpiry, '/', null, app()->environment('production'), true );

        return redirect()->intended('/')->withCookie($cookie);
    }

    /**
     * Create or update the user record with the primary IP.
     */
    protected function createOrUpdateUserRecord(string $email, string $primaryIp): UserAccessRecord
    {
        return UserAccessRecord::updateOrCreate(
            ['email' => $email],
            ['primary_ip' => $primaryIp]
        );
    }

    /**
     * Create or update the browser session.
     */
    protected function createOrUpdateBrowserSession(UserAccessRecord $record, string $clientIp, string $browser): UserAccessBrowser
    {
        $token = bin2hex(random_bytes(32)); // Generate secure token
        $tokenExpiry = config('access-guard.session_token_expiry', 180); // Default to 3 hours

        return $record->browsers()->updateOrCreate(
            ['browser' => $browser],
            [
                'session_ip' => $clientIp,
                'session_token' => $token,
                'otp_expires_at' => now()->addMinutes(10),
            ]
        );
    }

    /**
     * Generate and send an OTP to the user.
     */
    protected function generateAndSendOtp(UserAccessRecord $record, UserAccessBrowser $browserSession): void
    {
        $otp = rand(100000, 999999);

        $browserSession->update([
            'otp' => $otp,
        ]);

        $record->notify(new OTPNotification($otp));
    }

    /**
     * Mark the browser session and user record as verified.
     */
    protected function markSessionAsVerified(UserAccessBrowser $browserSession): void
    {
        $tokenExpiry = config('access-guard.session_token_expiry', 180); // Default to 3 hours

        $browserSession->update(['otp' => null, 'verified_at' => now(), 'expires_at' => now()->addMinutes($tokenExpiry)]);
        $browserSession->userAccessRecord->update(['last_verified_at' => now()]);
    }

}
