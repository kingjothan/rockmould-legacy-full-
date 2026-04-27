<?php

return [
    'types' => [
        'property_inquiry' => 'Property Inquiry',
        'project_consultation' => 'Project Consultation',
        'investment_discussion' => 'Investment Discussion',
        'site_inspection_request' => 'Site Inspection Request',
        'client_support' => 'Client Support',
        'general_business_consultation' => 'General Business Consultation',
    ],

    'statuses' => [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'no_show' => 'No Show',
    ],

    'booking' => [
        'timezone' => env('CONSULTATION_TIMEZONE', env('APP_TIMEZONE', 'Africa/Lagos')),
        'slot_duration_minutes' => (int) env('CONSULTATION_SLOT_DURATION', 60),
        'buffer_minutes' => (int) env('CONSULTATION_BUFFER_MINUTES', 15),
        'booking_window_days' => (int) env('CONSULTATION_BOOKING_WINDOW_DAYS', 60),
        'minimum_notice_hours' => (int) env('CONSULTATION_MINIMUM_NOTICE_HOURS', 12),
    ],

    'meeting_links' => [
        'mode' => env('CONSULTATION_MEETING_LINK_MODE', 'manual'),
        'provider' => env('CONSULTATION_MEETING_LINK_PROVIDER', 'manual'),
    ],
];
