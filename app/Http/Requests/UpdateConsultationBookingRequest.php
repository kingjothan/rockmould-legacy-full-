<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateConsultationBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdminRole();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'consultation_type' => $this->consultation_type ? Str::of((string) $this->consultation_type)->snake()->toString() : null,
            'start_time' => $this->start_time ? substr((string) $this->start_time, 0, 5) : null,
            'assigned_staff_id' => $this->assigned_staff_id ?: null,
            'meeting_link' => $this->meeting_link ?: null,
            'timezone' => $this->timezone ?: config('consultations.booking.timezone', config('app.timezone')),
        ]);
    }

    public function rules(): array
    {
        return [
            'consultation_type' => ['required', Rule::in(array_keys(config('consultations.types', [])))],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
            'consultation_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'timezone' => ['required', 'timezone'],
            'status' => ['required', Rule::in(array_keys(config('consultations.statuses', [])))],
            'assigned_staff_id' => ['nullable', 'integer', 'exists:users,id'],
            'meeting_link' => ['nullable', 'url', 'max:1000'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
