<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationsRequest extends FormRequest
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
            'notify_overdue_payments' => 'boolean',
            'notify_daily_distribution' => 'boolean',
            'notify_weekly_report' => 'boolean',
            'notify_pending_advances' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'notify_overdue_payments.boolean' => 'قيمة إشعار المدفوعات المتأخرة يجب أن تكون صحيحة',
            'notify_daily_distribution.boolean' => 'قيمة إشعار التوزيع اليومي يجب أن تكون صحيحة',
            'notify_weekly_report.boolean' => 'قيمة إشعار التقرير الأسبوعي يجب أن تكون صحيحة',
            'notify_pending_advances.boolean' => 'قيمة إشعار الطلبات المعلقة يجب أن تكون صحيحة',
        ];
    }
}
