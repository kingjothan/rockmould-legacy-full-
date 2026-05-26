<?php

namespace App\Providers;

use App\Contracts\ConsultationMeetingLinkProvider;
use App\Services\Consultations\ManualConsultationMeetingLinkProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ConsultationMeetingLinkProvider::class, function () {
            return match (config('consultations.meeting_links.provider', 'manual')) {
                default => new ManualConsultationMeetingLinkProvider,
            };
        });
    }

    public function boot(): void
    {
        RateLimiter::for('consultation-bookings', function (Request $request) {
            return Limit::perMinute(6)->by($request->ip());
        });

        RateLimiter::for('consultation-availability', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        RateLimiter::for('portal-otp-request', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perHour(10)->by($request->ip()),
            ];
        });

        RateLimiter::for('portal-otp-verify', function (Request $request) {
            return Limit::perMinute(6)->by($request->session()->get('portal_otp_user_id', $request->ip()));
        });
    }
}
