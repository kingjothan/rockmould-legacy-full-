<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConsultationBlockedDateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdminRole();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_all_day' => $this->boolean('is_all_day'),
        ]);
    }

    public function rules(): array
    {
        return [
            'blocked_on' => ['required', 'date_format:Y-m-d'],
            'is_all_day' => ['nullable', 'boolean'],
            'start_time' => [
                Rule::requiredIf(fn () => ! $this->boolean('is_all_day')),
                'nullable',
                'date_format:H:i',
            ],
            'end_time' => [
                Rule::requiredIf(fn () => ! $this->boolean('is_all_day')),
                'nullable',
                'date_format:H:i',
                'after:start_time',
            ],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
