<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'worker_id' => 'required|integer|exists:workers,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date_format:Y-m-d',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'worker_id.required' => 'معرّف العامل مطلوب',
            'worker_id.exists' => 'العامل المحدد غير موجود',
            'amount.required' => 'المبلغ مطلوب',
            'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من صفر',
            'date.required' => 'تاريخ المتقدم مطلوب',
            'date.date_format' => 'تاريخ غير صحيح',
            'notes.max' => 'الملاحظات لا يمكن أن تتجاوز 500 حرف',
        ];
    }
}
