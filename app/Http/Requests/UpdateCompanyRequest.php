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
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'daily_wage' => 'sometimes|required|numeric|min:0',
            'payment_cycle' => 'sometimes|required|in:daily,weekly,bimonthly',
            'weekly_pay_day' => 'nullable|string|max:20',
            'contract_start_date' => 'sometimes|required|date_format:Y-m-d',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الشركة مطلوب',
            'contact_person.required' => 'اسم جهة الاتصال مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'daily_wage.required' => 'الأجر اليومي مطلوب',
            'payment_cycle.required' => 'دورة الدفع مطلوبة',
            'contract_start_date.required' => 'تاريخ بدء العقد مطلوب',
        ];
    }
}
