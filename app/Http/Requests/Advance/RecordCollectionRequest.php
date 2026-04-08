<?php

namespace App\Http\Requests\Advance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecoveryMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'contractor';
    }

    public function rules(): array
    {
        return [
            'recovery_method' => ['required', 'in:immediately,installments,manually'],
            'installment_period' => ['nullable', 'in:weekly,biweekly', 'required_if:recovery_method,installments'],
            'installment_count' => ['nullable', 'integer', 'min:2', 'required_if:recovery_method,installments'],
        ];
    }

    public function messages(): array
    {
        return [
            'recovery_method.required' => 'حقل طريقة الاسترجاع مطلوب',
            'recovery_method.in' => 'طريقة الاسترجاع غير صحيحة',
            'installment_period.required_if' => 'فترة القسط مطلوبة عند اختيار الأقساط',
            'installment_period.in' => 'فترة القسط غير صحيحة',
            'installment_count.required_if' => 'عدد الأقساط مطلوب عند اختيار الأقساط',
            'installment_count.integer' => 'عدد الأقساط يجب أن يكون رقم',
            'installment_count.min' => 'عدد الأقساط يجب أن يكون على الأقل 2',
        ];
    }
}
