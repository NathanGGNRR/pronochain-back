<?php

namespace App\Http\Requests;

use App\Enums\FriendRequestsStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnswerFriendRequest extends FormRequest
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
            'requesting_user_id' => [
                'required',
                'exists:users,id',
            ],
            'status' => [
                'required',
                Rule::in([FriendRequestsStatus::ACCEPTED->value, FriendRequestsStatus::DECLINED->value]),
            ],
        ];
    }
}
