<?php

namespace Sharqlabs\LaravelAccessGuard\Http\Controllers;

use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;

class VerifiedEmailsController
{
    public function index()
    {
        // Fetch all verified emails and their associated browsers
        $verifiedEmails = UserAccessRecord::with(['browsers' => function ($query) {
            $query->whereNotNull('verified_at'); // Only fetch verified browsers
        }])->get();

        return view('laravel-access-guard::verified-emails', compact('verifiedEmails'));
    }
}
