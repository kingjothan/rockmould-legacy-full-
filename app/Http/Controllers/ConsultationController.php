<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultationBookingRequest;
use App\Mail\ConsultationBookedAdminMail;
use App\Mail\ConsultationBookedClientMail;
use App\Models\ConsultationBooking;
use App\Services\Consultations\ConsultationAvailabilityService;
use App\Services\Consultations\ConsultationBookingService;
use App\Support\SiteData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function create(Request $request, ConsultationAvailabilityService $availability): View
    {
        $consultationTypes = config('consultations.types', []);
        $selectedType = old('consultation_type', Str::snake((string) $request->query('type')));
        $selectedType = array_key_exists($selectedType, $consultationTypes) ? $selectedType : null;
        $selectedDate = old('consultation_date', $request->query('date'));
        $selectedMonth = $request->query('month');

        if (! is_string($selectedDate) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) !== 1) {
            $selectedDate = null;
        }

        if (! is_string($selectedMonth) || preg_match('/^\d{4}-\d{2}$/', $selectedMonth) !== 1) {
            $selectedMonth = null;
        }

        if ($selectedDate) {
            $selectedMonth = substr($selectedDate, 0, 7);
        }

        $moduleReady = $availability->tablesExist();
        $calendar = $availability->calendar($selectedMonth, $selectedDate);
        $slots = $selectedDate ? $availability->slotsForDate($selectedDate) : [];

        return view('site.consultations.create', [
            'company' => SiteData::company(),
            'consultationTypes' => $consultationTypes,
            'calendar' => $calendar,
            'slots' => $slots,
            'settings' => $availability->settings(),
            'selectedType' => $selectedType,
            'selectedDate' => $selectedDate,
            'selectedStartTime' => old('start_time'),
            'subjectPrefill' => old('subject', (string) $request->query('subject')),
            'moduleReady' => $moduleReady,
        ]);
    }

    public function availability(Request $request, ConsultationAvailabilityService $availability): JsonResponse
    {
        if (! $availability->tablesExist()) {
            return response()->json([
                'message' => 'Consultation scheduling is temporarily unavailable while the booking calendar is being finalized.',
            ], 503);
        }

        $data = $request->validate([
            'month' => ['nullable', 'regex:/^\d{4}-\d{2}$/'],
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $selectedDate = $data['date'] ?? null;
        $selectedMonth = $data['month'] ?? ($selectedDate ? substr($selectedDate, 0, 7) : null);
        $calendar = $availability->calendar($selectedMonth, $selectedDate);
        $slots = $selectedDate ? $availability->slotsForDate($selectedDate) : [];

        return response()->json([
            'calendar' => view('site.consultations.partials.calendar', [
                'calendar' => $calendar,
            ])->render(),
            'slots' => view('site.consultations.partials.slots', [
                'slots' => $slots,
                'selectedDate' => $selectedDate,
            ])->render(),
        ]);
    }

    public function store(
        StoreConsultationBookingRequest $request,
        ConsultationBookingService $bookingService,
        ConsultationAvailabilityService $availability,
    ): RedirectResponse {
        if (! $availability->tablesExist()) {
            return back()
                ->withInput()
                ->withErrors([
                    'consultation' => 'Consultation scheduling is temporarily unavailable. Please contact Rockmould directly while the booking calendar is being finalized.',
                ]);
        }

        $booking = $bookingService->create($request->validated());
        $company = SiteData::company();
        $settings = $availability->settings();
        $adminEmail = $settings->notification_email ?: ($company['support_email'] ?? $company['email'] ?? null);

        Mail::to($booking->email)->send(new ConsultationBookedClientMail($booking, $company));

        if ($adminEmail) {
            Mail::to($adminEmail)->send(
                new ConsultationBookedAdminMail($booking, $company, route('admin.consultations.show', $booking))
            );
        }

        return redirect()->route('consultations.success', $booking->reference);
    }

    public function success(string $reference): View|RedirectResponse
    {
        if (! Schema::hasTable('consultation_bookings')) {
            return redirect()
                ->route('consultations.create')
                ->withErrors([
                    'consultation' => 'Consultation scheduling is temporarily unavailable. Please contact Rockmould directly while the booking calendar is being finalized.',
                ]);
        }

        return view('site.consultations.success', [
            'company' => SiteData::company(),
            'booking' => ConsultationBooking::query()
                ->with('assignedStaff')
                ->where('reference', $reference)
                ->firstOrFail(),
        ]);
    }
}
