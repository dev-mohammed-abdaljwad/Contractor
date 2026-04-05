<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeductionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'worker_id' => 'required|integer|exists:workers,id',
            'company_id' => 'required|integer|exists:companies,id',
            'deduction_date' => 'required|date_format:Y-m-d',
            'type' => 'required|in:quarter,half,full,custom',
            'amount' => 'required_if:type,custom|nullable|numeric|min:0',
            'reason' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'worker_id.required' => 'معرّف العامل مطلوب',
            'worker_id.exists' => 'العامل المحدد غير موجود',
            'company_id.required' => 'معرّف الشركة مطلوب',
            'company_id.exists' => 'الشركة المحددة غير موجودة',
            'deduction_date.required' => 'تاريخ الخصم مطلوب',
            'deduction_date.date_format' => 'تاريخ غير صحيح',
            'type.required' => 'نوع الخصم مطلوب',
            'type.in' => 'نوع الخصم غير صحيح',
            'amount.required_if' => 'يجب تحديد المبلغ للخصم المخصص',
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
            'amount.min' => 'المبلغ يجب أن يكون موجباً',
            'reason.max' => 'السبب لا يمكن أن يتجاوز 500 حرف',
        ];
    }
}
