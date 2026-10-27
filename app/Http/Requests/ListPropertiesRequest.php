<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListPropertiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('search'))) {
            $this->merge([
                'search' => trim($this->input('search')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string', 'max:100'],
        ];
    }
}