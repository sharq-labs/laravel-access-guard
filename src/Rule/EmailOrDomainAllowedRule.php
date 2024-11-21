<?php

namespace Sharqlabs\LaravelAccessGuard\Rule;

use Illuminate\Contracts\Validation\Rule;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessRecord;
use Sharqlabs\LaravelAccessGuard\Models\UserAccessAllowedDomain;

class EmailOrDomainAllowedRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // Check if the email exists in the `user_access_records` table
        if (UserAccessRecord::query()->where('email', $value)->exists()) {
            return true;
        }

        // Extract the domain from the email
        $domain = $this->getDomainFromEmail($value);

        // Check if the domain exists in the `user_access_allowed_domains` table
        if (UserAccessAllowedDomain::query()->where('domain', $domain)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The email or its domain is not allowed.';
    }

    /**
     * Extract the domain from an email address.
     *
     * @param string $email
     * @return string|null
     */
    protected function getDomainFromEmail($email): ?string
    {
        return substr(strrchr($email, "@"), 1); // Extract the domain part of the email
    }
}
