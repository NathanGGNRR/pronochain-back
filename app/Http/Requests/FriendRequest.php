<?php

namespace App\Http\Requests;

use App\Rules\AcceptsInvitations;
use App\Rules\NotFriendWith;
use App\Rules\NotInPending;
use App\Rules\NotSpam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FriendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'requested_user_id' => [
                'required',
                'exists:users,id',
                new AcceptsInvitations(),
                Rule::notIn($this->route('user')->id),
                new NotFriendWith($this->route('user')),
                new NotInPending($this->route('user')),
                new NotSpam($this->route('user')),
            ],
        ];
    }
}
