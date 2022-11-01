<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PredictRequest extends FormRequest
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
    public function rules()
    {
        return [
            'odd_id' => [
                'required',
                'exists:odds,id',
            ],
        ];
    }
}
