<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nickname' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],

            'bio' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }
}