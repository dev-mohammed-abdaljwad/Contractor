<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemPreferencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'language' => 'required|in:ar,en',
            'currency' => 'required|in:EGP,USD,SAR',
            'date_format' => 'required|in:DD/MM/YYYY,MM/DD/YYYY,YYYY-MM-DD',
            'week_start' => 'required|in:Sunday,Monday,Saturday',
            'dark_mode' => 'boolean',
            'overtime_hourly_rate' => 'required|numeric|min:5|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'language.required' => 'اللغة مطلوبة',
            'language.in' => 'اللغة المختارة غير صحيحة',
            'currency.required' => 'العملة مطلوبة',
            'currency.in' => 'العملة المختارة غير صحيحة',
            'date_format.required' => 'صيغة التاريخ مطلوبة',
            'date_format.in' => 'صيغة التاريخ المختارة غير صحيحة',
            'week_start.required' => 'بداية الأسبوع مطلوبة',
            'week_start.in' => 'بداية الأسبوع المختارة غير صحيحة',
            'dark_mode.boolean' => 'الوضع الليلي يجب أن يكون صحيح أو خاطئ',
            'overtime_hourly_rate.required' => 'سعر ساعة السهر مطلوب',
            'overtime_hourly_rate.numeric' => 'سعر ساعة السهر يجب أن يكون رقماً',
            'overtime_hourly_rate.min' => 'سعر ساعة السهر لا يقل عن 5 ج',
            'overtime_hourly_rate.max' => 'سعر ساعة السهر لا يزيد عن 1000 ج',
        ];
    }
}
