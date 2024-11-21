<?php

namespace Sharqlabs\LaravelAccessGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserAccessRecord extends Model
{
    use Notifiable;

    protected $fillable = ['email', 'domain', 'last_verified_at'];

    /**
     * Define the relationship with browser sessions.
     */
    public function browsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserAccessBrowser::class);
    }

    /**
     * Set expires_at to null for all related browsers.
     */
    public function clearBrowserExpiry(): void
    {
        $this->browsers()->update(['expires_at' => null]);
    }

    /**
     * Mark the user as verified.
     */
    public function markVerified(): void
    {
        $this->update(['last_verified_at' => now()]);
    }
}
