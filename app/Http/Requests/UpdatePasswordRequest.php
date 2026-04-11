<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user()->password)) {
                        $fail('كلمة السر الحالية غير صحيحة');
                    }
                },
            ],
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
            'new_password_confirmation' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'كلمة السر الحالية مطلوبة',
            'new_password.required' => 'كلمة السر الجديدة مطلوبة',
            'new_password.min' => 'كلمة السر يجب أن تكون 8 أحرف على الأقل',
            'new_password.confirmed' => 'كلمتا السر غير متطابقتين',
            'new_password.different' => 'كلمة السر الجديدة يجب أن تكون مختلفة عن الحالية',
            'new_password_confirmation.required' => 'تأكيد كلمة السر مطلوب',
        ];
    }
}
