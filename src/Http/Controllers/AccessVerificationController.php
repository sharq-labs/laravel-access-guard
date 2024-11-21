<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Controllers;

use Illuminate\Http\Request;
use Sharqlabs\LaravelAccessGuard\Services\OtpService;
use Sharqlabs\LaravelAccessGuard\Services\UserAccessService;

class AccessVerificationController
{
    protected $userAccessService;
    protected $otpService;

    public function __construct(UserAccessService $userAccessService, OtpService $otpService)
    {
        $this->userAccessService = $userAccessService;
        $this->otpService = $otpService;
    }

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
        $sessionToken = $request->query('session_token');
        return view('laravel-access-guard::otp', compact('sessionToken'));
    }

    /**
     * Handle form submission for access verification.
     */
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:user_access_records,email'],
        ], [
            'email.exists' => 'The provided email is not registered on this domain',
        ]);

        try {
            $sessionToken = $this->userAccessService->handleAccessRequest(
                $validated['email'],
                $request->ip(),
                $request->header('User-Agent')
            );

            return redirect()->route('laravel-access-guard.otp-form', ['session_token' => $sessionToken]);
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to process your request. Please try again.']);
        }
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

        try {
            $this->otpService->verifyOtp(
                $validated['sessionToken'],
                $validated['otp'],
                $request->ip()
            );

            return redirect()->intended('/');
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => $e->getMessage()]);
        }
    }
}
