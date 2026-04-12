<?php

namespace App\Http\Requests\Overtime;

use Illuminate\Foundation\Http\FormRequest;

class BulkOvertimeRequest extends FormRequest
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
            'entries' => 'required|array|min:1',
            'entries.*.distribution_id' => 'required|integer|exists:daily_distributions,id',
            'entries.*.overtime_hours' => 'required|numeric|min:0|max:12',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'entries.required' => 'يجب تحديد توزيعات على الأقل',
            'entries.array' => 'صيغة البيانات غير صحيحة',
            'entries.min' => 'يجب تحديد توزيع واحد على الأقل',
            'entries.*.distribution_id.required' => 'معرف التوزيع مطلوب',
            'entries.*.distribution_id.exists' => 'التوزيع غير موجود',
            'entries.*.overtime_hours.required' => 'عدد الساعات مطلوب',
            'entries.*.overtime_hours.numeric' => 'عدد الساعات يجب أن يكون رقمياً',
            'entries.*.overtime_hours.min' => 'عدد الساعات يجب أن يكون أكبر من أو يساوي 0',
            'entries.*.overtime_hours.max' => 'عدد الساعات يجب أن لا يزيد عن 12 ساعة',
        ];
    }
}
