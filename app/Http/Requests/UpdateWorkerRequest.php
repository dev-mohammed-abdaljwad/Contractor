<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Allow partial updates: only validate fields that are provided
        return [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'national_id' => 'nullable|string|max:20',
            'joined_date' => 'nullable|date_format:Y-m-d',
            'is_active' => 'nullable|boolean',
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

