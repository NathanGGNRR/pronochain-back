<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class NotFriendWith implements Rule
{
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

        return !$requested_user->friends->contains($this->user);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return trans('validation.not_friend_with', ['user' => $this->user->id]);
    }
}
