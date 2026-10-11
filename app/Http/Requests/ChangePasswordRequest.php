<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                'current_password', // فحص كلمة المرور في قاعدة البيانات
            ],

            'password' => [
                'required',
                'string',
                'confirmed',
                'different:current_password', 
                Password::defaults(),
            ],
        ];
    }

    public function messages()
    {
        return [
            // رسالة الخطأ في حال كانت كلمة المرور القديمة غير صحيحة
            'current_password.current_password' => 'Current password is incorrect.',
            
            'password.different' => 'The new password cannot be the same as the current one.',
            'password.required' => 'Please enter a new password.',
            'current_password.required' => 'Please enter your current password.',
        ];
    }
}