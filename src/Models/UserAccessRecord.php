<?php

namespace Sharqlabs\LaravelAccessGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserAccessRecord extends Model
{
    use Notifiable;

    protected $fillable = ['email', 'primary_ip', 'no_expiration', 'last_verified_at'];

    /**
     * Define the relationship with browser sessions.
     */
    public function browsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserAccessBrowser::class);
    }

    /**
     * Mark the user as verified.
     */
    public function markVerified(): void
    {
        $this->update(['last_verified_at' => now()]);
    }

    /**
     * Check if the user's record does not expire.
     */
    public function doesNotExpire(): bool
    {
        return $this->no_expiration;
    }
}
