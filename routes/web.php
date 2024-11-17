<?php

use Sharqlabs\LaravelAccessGuard\Http\Controllers\AccessVerificationController;
use Illuminate\Support\Facades\Route;
use Sharqlabs\LaravelAccessGuard\Http\Middleware\RedirectIfAlreadyVerified;


Route::middleware(['web', RedirectIfAlreadyVerified::class])->group(function () {
    Route::get('/access-verify', [AccessVerificationController::class, 'showForm'])->name('laravel-access-guard.form');
    Route::post('/access-verify', [AccessVerificationController::class, 'submitForm'])->name('laravel-access-guard.submit');
    Route::get('/otp-verify', [AccessVerificationController::class, 'showOtpForm'])->name('laravel-access-guard.otp-form');
    Route::post('/verify-otp', [AccessVerificationController::class, 'verifyOtp'])->name('laravel-access-guard.verify-otp');
});
