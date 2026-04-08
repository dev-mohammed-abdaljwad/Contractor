<?php

namespace App\Http\Requests\Deduction;

use Illuminate\Foundation\Http\FormRequest;

class ReverseDeductionRequest extends FormRequest
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
            'reversal_reason' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'reversal_reason.string' => 'السبب يجب أن يكون نصاً',
            'reversal_reason.max' => 'السبب يجب ألا يتجاوز 1000 حرف',
        ];
    }
}
