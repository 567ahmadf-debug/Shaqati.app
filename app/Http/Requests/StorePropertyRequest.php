<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isLandlord() ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255',],

            'description' => ['required', 'string', 'max:5000',],

            'property_type' => ['required', 'string', 'max:50',],

            'rental_price' => ['required', 'numeric', 'gt:0', 'decimal:0,2',],

            'currency' => ['required', 'string', 'regex:/^[A-Z]{3}$/',],

            'area' => ['nullable', 'numeric', 'gt:0', 'decimal:0,2',],

            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:100',],

            'bathrooms' => ['nullable', 'integer', 'min:0', 'max:100',],

            'address' => ['required', 'string', 'max:255',],

            'city' => ['required', 'string', 'max:255',],

            'latitude' => ['nullable', 'numeric', 'between:-90,90',],

            'longitude' => ['nullable', 'numeric', 'between:-180,180',],
        ];
    }
}
