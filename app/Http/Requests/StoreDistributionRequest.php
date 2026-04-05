<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'distribution_date' => 'required|date_format:Y-m-d',
            'assignments' => 'required|array|min:1',
            'assignments.*.company_id' => 'required|integer|exists:companies,id',
            'assignments.*.worker_id' => 'required|integer|exists:workers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'distribution_date.required' => 'تاريخ التوزيع مطلوب',
            'distribution_date.date_format' => 'تاريخ غير صحيح',
            'assignments.required' => 'يجب تحديد على الأقل عاملاً واحداً',
            'assignments.min' => 'يجب تحديد على الأقل عاملاً واحداً',
            'assignments.*.company_id.required' => 'معرّف الشركة مطلوب',
            'assignments.*.company_id.exists' => 'الشركة المحددة غير موجودة',
            'assignments.*.worker_id.required' => 'معرّف العامل مطلوب',
            'assignments.*.worker_id.exists' => 'العامل المحدد غير موجود',
        ];
    }
}
