<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'credentials' => [
                'required',
                'array:username,message,address,signature',
            ],
            'credentials.username' => ['required',
                'string',
                'unique:users,username',
            ],
            'credentials.message' => [
                'required',
            ],
            'credentials.address' => [
                'required',
                'string',
                'unique:users,eth_address',
            ],
            'credentials.signature' => [
                'required',
                'string',
            ],
        ];
    }
}
