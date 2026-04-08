<?php

namespace App\Http\Requests\Deduction;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeductionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'worker_id' => 'required|integer|exists:workers,id',
            'date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'type' => 'required|in:quarter,half,full,custom',
            'amount' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'worker_id.required' => 'العامل مطلوب',
            'worker_id.exists' => 'العامل غير موجود',
            'date.required' => 'التاريخ مطلوب',
            'date.date_format' => 'التاريخ غير صحيح',
            'date.before_or_equal' => 'يجب أن يكون التاريخ اليوم أو أقدم',
            'type.required' => 'نوع الخصم مطلوب',
            'type.in' => 'نوع الخصم غير صحيح',
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من أو يساوي صفر',
            'reason.string' => 'السبب يجب أن يكون نصاً',
            'reason.max' => 'السبب يجب ألا يتجاوز 500 حرف',
        ];
    }
}
