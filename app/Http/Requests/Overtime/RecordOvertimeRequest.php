<?php

namespace App\Http\Requests\Overtime;

use Illuminate\Foundation\Http\FormRequest;

class RecordOvertimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isContractor();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'distribution_id' => 'required|integer|exists:daily_distributions,id',
            'overtime_hours' => 'required|numeric|min:0|max:12',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'distribution_id.required' => 'معرف التوزيع مطلوب',
            'distribution_id.exists' => 'التوزيع غير موجود',
            'overtime_hours.required' => 'عدد الساعات مطلوب',
            'overtime_hours.numeric' => 'عدد الساعات يجب أن يكون رقمياً',
            'overtime_hours.min' => 'عدد الساعات يجب أن يكون أكبر من أو يساوي 0',
            'overtime_hours.max' => 'عدد الساعات يجب أن لا يزيد عن 12 ساعة',
        ];
    }
}
