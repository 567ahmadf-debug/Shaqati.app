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
        $stringFields = [
            'search',
            'city',
            'property_type',
            'availability_status',
        ];

        $normalized = [];

        foreach ($stringFields as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $normalized[$field] = trim($value);
            }
        }

        $this->merge($normalized);
    }

    public function rules(): array
    {
        return [
            'search' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],

            'city' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],

            'property_type' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
            ],

            'availability_status' => [
                'sometimes',
                'nullable',
                'string',
                'max:30',
            ],

            'min_price' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_price' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
                'gte:min_price',
            ],
        ];
    }
}
