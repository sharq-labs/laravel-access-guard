<?php

namespace Sharqlabs\LaravelAccessGuard\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccessBrowser extends Model
{
    protected $fillable = [
        'user_access_record_id',
        'session_token',
        'session_ip',
        'browser',
        'otp',
        'expires_at',
        'verified_at'
    ];

    /**
     * The attributes that should be cast to specific types.
     */
    protected $casts = [
        'user_access_record_id' => 'integer',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'otp' => 'integer', // Assuming OTP is an integer
    ];

    /**
     * Define the relationship with the user record.
     */
    public function userAccessRecord()
    {
        return $this->belongsTo(UserAccessRecord::class);
    }

    /**
     * Mark the session as verified.
     */
    public function markVerified()
    {
        $this->update(['verified_at' => now()]);
    }

    /**
     * Check if the session is expired.
     */
    public function isExpired(): bool
    {
        // Check if the parent user record does not expire
        if ($this->userAccessRecord->no_expiration) {
            return false;
        }

        // If no expiration time is set, assume it is expired
        if (is_null($this->expires_at)) {
            return true;
        }

        // Check if the session is expired by the default or given minutes
        return $this->expires_at->isPast();
    }

}
