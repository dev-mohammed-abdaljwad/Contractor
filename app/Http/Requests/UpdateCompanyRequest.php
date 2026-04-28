<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'daily_wage' => 'sometimes|required|numeric|min:0',
            'contractor_rate' => 'sometimes|required|numeric|min:0',
            'overtime_rate' => 'sometimes|required|numeric|min:0',
            'contract_start_date' => 'sometimes|required|date_format:Y-m-d',
            // Legacy fields (accepted but not required for update)
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'payment_cycle' => 'sometimes|in:daily,weekly,bimonthly',
            'weekly_pay_day' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الشركة مطلوب',
            'daily_wage.required' => 'الأجر اليومي مطلوب',
            'overtime_rate.required' => 'أجر ساعة السهر مطلوب',
            'contract_start_date.required' => 'تاريخ بدء التعاقد مطلوب',
        ];
    }
}
