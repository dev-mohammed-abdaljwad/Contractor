<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'worker_id' => 'required|integer|exists:workers,id',
            'reason' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'معرّف الشركة مطلوب',
            'company_id.exists' => 'الشركة المحددة غير موجودة',
            'worker_id.required' => 'معرّف العامل مطلوب',
            'worker_id.exists' => 'العامل المحدد غير موجود',
            'reason.max' => 'السبب لا يجب أن يتجاوز 500 حرف',
        ];
    }
}
