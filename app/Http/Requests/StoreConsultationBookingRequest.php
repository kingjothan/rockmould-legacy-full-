<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreConsultationBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'consultation_type' => $this->consultation_type ? Str::of((string) $this->consultation_type)->snake()->toString() : null,
            'start_time' => $this->start_time ? substr((string) $this->start_time, 0, 5) : null,
            'timezone' => $this->timezone ?: config('consultations.booking.timezone', config('app.timezone')),
        ]);
    }

    public function rules(): array
    {
        return [
            'consultation_type' => ['required', Rule::in(array_keys(config('consultations.types', [])))],
            'consultation_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
            'timezone' => ['nullable', 'timezone'],
            'website' => ['nullable', 'string', 'max:0'],
        ];
    }
}
