<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => ['required', 'string', 'regex:/^01[0-2]\d{8}$/', Rule::unique('users', 'phone')->ignore($this->user()->id)],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'phone_backup' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'اسم العائلة مطلوب',
            'phone.required' => 'رقم التليفون مطلوب',
            'phone.regex' => 'رقم التليفون يجب أن يكون بصيغة مصرية صحيحة (01XXXXXXXXX)',
            'phone.unique' => 'رقم التليفون مسجل بالفعل',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مسجل بالفعل',
        ];
    }
}
