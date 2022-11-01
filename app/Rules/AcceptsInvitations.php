<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class AcceptsInvitations implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        $requested_user = User::findOrFail($value);

        return !$requested_user->has_blocked_invitation;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return trans('validation.blocked_invitations');
    }
}
