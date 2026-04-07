<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'joined_date' => 'nullable|date_format:Y-m-d',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم العامل مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'joined_date.date_format' => 'تاريخ الالتحاق يجب أن يكون بصيغة صحيحة',
        ];
    }
}
