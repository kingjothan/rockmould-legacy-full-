<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultationSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdminRole();
    }

    public function rules(): array
    {
        return [
            'timezone' => ['required', 'timezone'],
            'slot_duration_minutes' => ['required', 'integer', 'min:30', 'max:180'],
            'buffer_minutes' => ['required', 'integer', 'min:0', 'max:120'],
            'booking_window_days' => ['required', 'integer', 'min:7', 'max:180'],
            'minimum_notice_hours' => ['required', 'integer', 'min:0', 'max:168'],
            'notification_email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
