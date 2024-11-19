<?php

namespace Sharqlabs\LaravelAccessGuard\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Sharqlabs\LaravelAccessGuard\Services\AccessGuardService;

class EmailWithDomain implements Rule
{
    /**
     * The domain to validate against.
     *
     * @var string
     */
    protected $domain;

    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        $this->domain = AccessGuardService::getCurrentUrlWithoutSubdomain();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the email exists in the database with the required domain
        return DB::table('user_access_records')
            ->where('email', $value)
            ->where('domain', $this->domain)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The provided email is not registered or does not belong to the required domain.';
    }
}
