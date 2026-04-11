<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notify_overdue_payments' => 'boolean',
            'notify_daily_distribution' => 'boolean',
            'notify_weekly_report' => 'boolean',
            'notify_pending_advances' => 'boolean',
            'language' => 'required|in:ar,en',
            'currency' => 'required|in:EGP,USD,SAR',
            'date_format' => 'required|in:DD/MM/YYYY,MM/DD/YYYY,YYYY-MM-DD',
            'week_start' => 'required|in:Sunday,Monday,Saturday',
            'dark_mode' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'language.required' => 'اللغة مطلوبة',
            'currency.required' => 'العملة مطلوبة',
            'date_format.required' => 'تنسيق التاريخ مطلوب',
            'week_start.required' => 'بداية الأسبوع مطلوبة',
        ];
    }
}
