<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class NotSpam implements Rule
{
    private const NUMBER_CONSIDERED_AS_SPAM = 3;
    private User $user;

    /**
     * Create a new rule instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        $requested_user = User::findOrFail($value);

        return !$requested_user->declinedFriendRequests()->where([
            ['user_id', '=', $this->user->id],
            ['requests_count', '>=', self::NUMBER_CONSIDERED_AS_SPAM],
        ])->exists();
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return trans('validation.too_many_requests', ['user' => $this->user->id]);
    }
}
