<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isLandlord() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'sometimes', 'required', 'string', 'max:255',
            ],

            'description' => [
                'sometimes', 'required', 'string', 'max:5000',
            ],

            'property_type' => [
                'sometimes', 'required', 'string', 'max:50',
            ],

            'rental_price' => [
                'sometimes', 'required', 'numeric',
                'gt:0', 'decimal:0,2',
            ],

            'currency' => [
                'sometimes', 'required', 'string',
                'regex:/^[A-Z]{3}$/',
            ],

            'area' => [
                'sometimes', 'nullable', 'numeric',
                'gt:0', 'decimal:0,2',
            ],

            'bedrooms' => [
                'sometimes', 'nullable', 'integer',
                'min:0', 'max:100',
            ],

            'bathrooms' => [
                'sometimes', 'nullable', 'integer',
                'min:0', 'max:100',
            ],

            'address' => [
                'sometimes', 'required', 'string', 'max:255',
            ],

            'city' => [
                'sometimes', 'required', 'string', 'max:255',
            ],

            'latitude' => [
                'sometimes', 'nullable', 'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'sometimes', 'nullable', 'numeric',
                'between:-180,180',
            ],
        ];
    }
}
