<?php

use Sharqlabs\LaravelAccessGuard\Http\Controllers\AccessVerificationController;
use Sharqlabs\LaravelAccessGuard\Http\Controllers\VerifiedEmailsController;
use Illuminate\Support\Facades\Route;
use Sharqlabs\LaravelAccessGuard\Http\Middleware\RedirectIfAlreadyVerified;
use Sharqlabs\LaravelAccessGuard\Http\Middleware\VerifySessionTokenMiddleware;


Route::middleware(['web', RedirectIfAlreadyVerified::class, 'throttle:access-guard'])
    ->group(function () {
        Route::get('/access-verify', [AccessVerificationController::class, 'showForm'])
            ->name('laravel-access-guard.form');

        Route::post('/access-verify', [AccessVerificationController::class, 'submitForm'])
            ->name('laravel-access-guard.submit');

        Route::get('/otp-verify', [AccessVerificationController::class, 'showOtpForm'])->middleware(VerifySessionTokenMiddleware::class)
            ->name('laravel-access-guard.otp-form');

        Route::post('/verify-otp', [AccessVerificationController::class, 'verifyOtp'])
            ->name('laravel-access-guard.verify-otp');
    });


Route::middleware(['web', 'auth.basic.config'])
    ->group(function () {
        Route::get('/verified-emails', [VerifiedEmailsController::class, 'index'])
            ->name('laravel-access-guard.verified-emails');
    });
