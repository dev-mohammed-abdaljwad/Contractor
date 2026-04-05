<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'phone'    => ['required', 'string', 'regex:/^01[0-9]{9}$/'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.regex'    => 'يرجى إدخال رقم هاتف مصري صحيح (01X XXXX XXXX).',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min'      => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.',
        ];
    }
}
