<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsultationAvailabilityRequest;
use App\Http\Requests\StoreConsultationBlockedDateRequest;
use App\Http\Requests\UpdateConsultationBookingRequest;
use App\Http\Requests\UpdateConsultationSettingsRequest;
use App\Mail\ConsultationBookingUpdatedMail;
use App\Models\ConsultationAvailability;
use App\Models\ConsultationBlockedDate;
use App\Models\ConsultationBooking;
use App\Models\User;
use App\Services\Consultations\ConsultationAvailabilityService;
use App\Services\Consultations\ConsultationBookingService;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function index(Request $request, ConsultationAvailabilityService $availability): View
    {
        $search = trim((string) $request->string('q'));
        $status = (string) $request->string('status');
        $settings = $availability->settings();
        $moduleReady = $availability->tablesExist();

        if (! $moduleReady) {
            return view('admin.consultations.index', [
                'company' => SiteData::company(),
                'bookings' => new LengthAwarePaginator([], 0, 12, 1, [
                    'path' => $request->url(),
                    'pageName' => 'page',
                ]),
                'consultationTypes' => config('consultations.types', []),
                'statuses' => config('consultations.statuses', []),
                'settings' => $settings,
                'availabilityWindows' => collect(),
                'blockedDates' => collect(),
                'staffMembers' => User::query()
                    ->whereIn('role', ['admin', 'editor', 'project_manager', 'finance', 'content_manager'])
                    ->where('status', 'active')
                    ->orderBy('name')
                    ->get(),
                'stats' => [
                    'total' => 0,
                    'pending' => 0,
                    'confirmed' => 0,
                    'upcoming' => 0,
                    'completed' => 0,
                ],
                'moduleReady' => false,
            ]);
        }

        $bookings = ConsultationBooking::query()
            ->with('assignedStaff')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested
                        ->where('full_name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%')
                        ->orWhere('consultation_type', 'like', '%'.$search.'%')
                        ->orWhereRaw("REPLACE(consultation_type, '_', ' ') LIKE ?", ['%'.$search.'%'])
                        ->orWhere('subject', 'like', '%'.$search.'%');
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->orderBy('consultation_date')
            ->orderBy('start_time')
            ->paginate(12)
            ->withQueryString();

        return view('admin.consultations.index', [
            'company' => SiteData::company(),
            'bookings' => $bookings,
            'consultationTypes' => config('consultations.types', []),
            'statuses' => config('consultations.statuses', []),
            'settings' => $settings,
            'availabilityWindows' => ConsultationAvailability::query()
                ->orderBy('day_of_week')
                ->orderBy('sort_order')
                ->orderBy('start_time')
                ->get(),
            'blockedDates' => ConsultationBlockedDate::query()
                ->orderBy('blocked_on')
                ->limit(20)
                ->get(),
            'staffMembers' => User::query()
                ->whereIn('role', ['admin', 'editor', 'project_manager', 'finance', 'content_manager'])
                ->where('status', 'active')
                ->orderBy('name')
                ->get(),
            'stats' => [
                'total' => ConsultationBooking::query()->count(),
                'pending' => ConsultationBooking::query()->where('status', ConsultationBooking::STATUS_PENDING)->count(),
                'confirmed' => ConsultationBooking::query()->where('status', ConsultationBooking::STATUS_CONFIRMED)->count(),
                'upcoming' => ConsultationBooking::query()
                    ->whereIn('status', ConsultationBooking::blockingStatuses())
                    ->whereDate('consultation_date', '>=', now($settings->timezone)->toDateString())
                    ->count(),
                'completed' => ConsultationBooking::query()->where('status', ConsultationBooking::STATUS_COMPLETED)->count(),
            ],
            'moduleReady' => true,
        ]);
    }

    public function show(ConsultationBooking $consultationBooking, ConsultationAvailabilityService $availability): View
    {
        return view('admin.consultations.show', [
            'company' => SiteData::company(),
            'booking' => $consultationBooking->load('assignedStaff'),
            'consultationTypes' => config('consultations.types', []),
            'statuses' => config('consultations.statuses', []),
            'settings' => $availability->settings(),
            'staffMembers' => User::query()
                ->whereIn('role', ['admin', 'editor', 'project_manager', 'finance', 'content_manager'])
                ->where('status', 'active')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(
        UpdateConsultationBookingRequest $request,
        ConsultationBooking $consultationBooking,
        ConsultationBookingService $bookingService,
    ): RedirectResponse {
        $result = $bookingService->updateFromAdmin($consultationBooking, $request->validated());
        $booking = $result['booking'];

        if ($result['meeting_link_changed'] || $result['schedule_changed'] || $result['status_changed']) {
            Mail::to($booking->email)->send(new ConsultationBookingUpdatedMail(
                $booking,
                SiteData::company(),
                includeMeetingLink: $result['meeting_link_changed'],
                scheduleChanged: $result['schedule_changed'],
                statusChanged: $result['status_changed'],
            ));
        }

        return back()->with('success', 'Consultation booking updated.');
    }

    public function updateSettings(
        UpdateConsultationSettingsRequest $request,
        ConsultationAvailabilityService $availability,
    ): RedirectResponse {
        if (! $availability->tablesExist()) {
            return back()->withErrors([
                'consultation_module' => 'Consultation scheduling tables are missing. Run php artisan migrate to finish enabling this module.',
            ]);
        }

        $settings = $availability->settings();
        $settings->fill($request->validated())->save();

        Activity::log('consultation.settings.updated', 'Consultation settings updated.', $request->user(), $settings);

        return back()->with('success', 'Consultation settings updated.');
    }

    public function storeAvailability(
        StoreConsultationAvailabilityRequest $request,
        ConsultationAvailabilityService $availability,
    ): RedirectResponse {
        if (! $availability->tablesExist()) {
            return back()->withErrors([
                'consultation_module' => 'Consultation scheduling tables are missing. Run php artisan migrate to finish enabling this module.',
            ]);
        }

        $window = ConsultationAvailability::query()->create([
            ...$request->validated(),
            'start_time' => $request->validated('start_time').':00',
            'end_time' => $request->validated('end_time').':00',
        ]);

        Activity::log('consultation.availability.created', 'Consultation availability window created.', $request->user(), $window);

        return back()->with('success', 'Working schedule updated.');
    }

    public function updateAvailability(
        StoreConsultationAvailabilityRequest $request,
        ConsultationAvailability $consultationAvailability,
    ): RedirectResponse {
        $consultationAvailability->fill([
            ...$request->validated(),
            'start_time' => $request->validated('start_time').':00',
            'end_time' => $request->validated('end_time').':00',
        ])->save();

        Activity::log('consultation.availability.updated', 'Consultation availability window updated.', $request->user(), $consultationAvailability);

        return back()->with('success', 'Availability window updated.');
    }

    public function destroyAvailability(Request $request, ConsultationAvailability $consultationAvailability): RedirectResponse
    {
        Activity::log('consultation.availability.deleted', 'Consultation availability window removed.', $request->user(), $consultationAvailability);
        $consultationAvailability->delete();

        return back()->with('success', 'Availability window removed.');
    }

    public function storeBlockedDate(StoreConsultationBlockedDateRequest $request): RedirectResponse
    {
        if (! app(ConsultationAvailabilityService::class)->tablesExist()) {
            return back()->withErrors([
                'consultation_module' => 'Consultation scheduling tables are missing. Run php artisan migrate to finish enabling this module.',
            ]);
        }

        $blockedDate = ConsultationBlockedDate::query()->create([
            'blocked_on' => $request->validated('blocked_on'),
            'is_all_day' => $request->boolean('is_all_day'),
            'start_time' => $request->validated('start_time') ? $request->validated('start_time').':00' : null,
            'end_time' => $request->validated('end_time') ? $request->validated('end_time').':00' : null,
            'reason' => $request->validated('reason'),
        ]);

        Activity::log('consultation.blocked-date.created', 'Consultation blocked date added.', $request->user(), $blockedDate);

        return back()->with('success', 'Blocked date saved.');
    }

    public function destroyBlockedDate(Request $request, ConsultationBlockedDate $consultationBlockedDate): RedirectResponse
    {
        Activity::log('consultation.blocked-date.deleted', 'Consultation blocked date removed.', $request->user(), $consultationBlockedDate);
        $consultationBlockedDate->delete();

        return back()->with('success', 'Blocked date removed.');
    }
}
