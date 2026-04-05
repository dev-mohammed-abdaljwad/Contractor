<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'period_start' => 'required|date_format:Y-m-d',
            'period_end' => 'required|date_format:Y-m-d|after_or_equal:period_start',
            'payment_method' => 'nullable|in:cash,transfer,cheque',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'معرّف الشركة مطلوب',
            'company_id.exists' => 'الشركة المحددة غير موجودة',
            'period_start.required' => 'تاريخ البداية مطلوب',
            'period_start.date_format' => 'تاريخ غير صحيح',
            'period_end.required' => 'تاريخ النهاية مطلوب',
            'period_end.date_format' => 'تاريخ غير صحيح',
            'period_end.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو مساوياً لتاريخ البداية',
            'payment_method.in' => 'طريقة الدفع غير صحيحة',
        ];
    }
}
