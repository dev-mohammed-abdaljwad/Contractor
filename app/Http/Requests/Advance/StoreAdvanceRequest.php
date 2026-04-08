<?php

namespace App\Http\Requests\Advance;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'contractor';
    }

    public function rules(): array
    {
        return [
            'worker_id' => ['required', 'integer', 'exists:workers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'recovery_method' => ['required', 'in:immediately,installments,manually'],
            'installment_period' => ['nullable', 'in:weekly,biweekly', 'required_if:recovery_method,installments'],
            'installment_count' => ['nullable', 'integer', 'min:2', 'required_if:recovery_method,installments'],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'worker_id.required' => 'حقل العامل مطلوب',
            'worker_id.exists' => 'هذا العامل غير موجود',
            'amount.required' => 'حقل المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقم',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من صفر',
            'date.required' => 'حقل التاريخ مطلوب',
            'date.date' => 'التاريخ غير صحيح',
            'date.before_or_equal' => 'التاريخ يجب أن يكون اليوم أو فيما مضى',
            'recovery_method.required' => 'حقل طريقة الاسترجاع مطلوب',
            'recovery_method.in' => 'طريقة الاسترجاع غير صحيحة',
            'installment_period.required_if' => 'فترة القسط مطلوبة عند اختيار الأقساط',
            'installment_period.in' => 'فترة القسط غير صحيحة',
            'installment_count.required_if' => 'عدد الأقساط مطلوب عند اختيار الأقساط',
            'installment_count.integer' => 'عدد الأقساط يجب أن يكون رقم',
            'installment_count.min' => 'عدد الأقساط يجب أن يكون على الأقل 2',
            'reason.max' => 'السبب لا يجب أن يتجاوز 500 حرف',
        ];
    }
}
