<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ClientOtp;
use App\Models\KycRequest;
use App\Models\Project;
use App\Models\User;
use App\Services\Otp\EmailOtpService;
use App\Support\PublicStorage;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    public function __construct(private readonly EmailOtpService $otpService) {}

    public function showLogin(Request $request): View
    {
        $state = session('portal_state');

        if (! $state && $request->query('mode') === 'kyc') {
            $state = 'kyc';
        }

        return view('portal.login', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('login'),
            'projectOptions' => Project::query()
                ->published()
                ->orderBy('title')
                ->get(['id', 'title', 'location', 'status']),
            'state' => $state ?: 'login',
            'otpEmail' => session('portal_otp_email'),
            'otpCooldownSeconds' => $this->remainingCooldown($request),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $normalized = $this->otpService->normalizeEmail($data['email']);
        $user = User::query()
            ->where('role', 'client')
            ->whereRaw('LOWER(email) = ?', [$normalized])
            ->first();

        if (! $user) {
            return back()->withInput()->with('portal_state', 'unrecognized');
        }

        if ($user->status !== 'active') {
            return back()->withErrors(['email' => 'This client account is not active yet.'])->with('portal_state', 'pending');
        }

        try {
            $otp = $this->otpService->send($user, $request);
        } catch (Throwable $exception) {
            Log::error('Client portal email OTP send failed', ['user_id' => $user->id, 'message' => $exception->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['email' => 'We could not email an OTP right now. Please try again in a few minutes.']);
        }

        $request->session()->put([
            'portal_otp_user_id' => $user->id,
            'portal_otp_email' => $this->maskedEmail($user->email),
            'portal_otp_last_sent_at' => optional($otp->sent_at)->timestamp ?? now()->timestamp,
        ]);

        return redirect()->route('portal.login')->with('portal_state', 'otp')->with('success', 'We sent a verification code to your email.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('portal_otp_user_id');

        if (! $userId) {
            return redirect()->route('portal.login')->withErrors(['otp' => 'Please enter your email address to request a fresh OTP.']);
        }

        $otp = ClientOtp::query()
            ->where('user_id', $userId)
            ->whereNull('consumed_at')
            ->latest()
            ->first();

        if (! $otp) {
            return redirect()->route('portal.login')->withErrors(['otp' => 'No active OTP was found. Please request a new code.']);
        }

        if ($otp->attempts >= (int) config('services.otp.max_attempts', 5)) {
            $otp->update(['consumed_at' => now(), 'delivery_status' => 'locked']);

            return redirect()->route('portal.login')->with('portal_state', 'otp')->withErrors(['otp' => 'Too many incorrect attempts. Please request a new OTP.']);
        }

        if (! Hash::check($data['otp'], $otp->code_hash)) {
            $otp->increment('attempts');

            return redirect()->route('portal.login')->with('portal_state', 'otp')->withErrors(['otp' => 'The OTP you entered is incorrect.']);
        }

        $user = User::query()->where('role', 'client')->where('status', 'active')->find($userId);

        if (! $user) {
            return redirect()->route('portal.login')->withErrors(['otp' => 'This client account is no longer active.']);
        }

        $otp->update(['consumed_at' => now(), 'delivery_status' => 'verified']);
        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->forget(['portal_otp_user_id', 'portal_otp_email', 'portal_otp_last_sent_at']);
        $user->forceFill(['last_active_at' => now()])->save();

        return redirect()->route('portal.dashboard');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('portal_otp_user_id');

        if (! $userId) {
            return redirect()->route('portal.login')->withErrors(['email' => 'Please enter your email address to request an OTP.']);
        }

        $remaining = $this->remainingCooldown($request);

        if ($remaining > 0) {
            return redirect()->route('portal.login')
                ->with('portal_state', 'otp')
                ->withErrors(['otp' => 'Please wait '.$remaining.' seconds before requesting another OTP.']);
        }

        $user = User::query()->where('role', 'client')->where('status', 'active')->find($userId);

        if (! $user) {
            return redirect()->route('portal.login')->withErrors(['email' => 'This client account is not active.']);
        }

        try {
            $otp = $this->otpService->send($user, $request);
        } catch (Throwable $exception) {
            Log::error('Client portal email OTP resend failed', ['user_id' => $user->id, 'message' => $exception->getMessage()]);

            return redirect()->route('portal.login')
                ->with('portal_state', 'otp')
                ->withErrors(['otp' => 'We could not resend the OTP email right now. Please try again shortly.']);
        }

        $request->session()->put([
            'portal_otp_email' => $this->maskedEmail($user->email),
            'portal_otp_last_sent_at' => optional($otp->sent_at)->timestamp ?? now()->timestamp,
        ]);

        return redirect()->route('portal.login')->with('portal_state', 'otp')->with('success', 'A new OTP has been sent to your email.');
    }

    public function kyc(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'residential_address' => ['required', 'string'],
            'project_id' => [
                'nullable',
                'integer',
                Rule::exists('projects', 'id')->where(fn ($query) => $query->where('is_published', true)),
            ],
            'project_of_interest' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'identification' => ['nullable', 'file', 'max:4096'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('portal_state', 'kyc');
        }

        $data = $validator->validated();
        $project = isset($data['project_id'])
            ? Project::query()->published()->find($data['project_id'])
            : null;

        $identification = $request->file('identification');
        $path = $identification ? PublicStorage::store($identification, 'kyc') : null;

        KycRequest::query()->create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'email' => $this->otpService->normalizeEmail($data['email']),
            'residential_address' => $data['residential_address'],
            'project_of_interest' => $project?->title ?? ($data['project_of_interest'] ?? null),
            'project_id' => $project?->id,
            'note' => $data['note'] ?? null,
            'identification_path' => $path,
            'status' => 'pending',
        ]);

        return back()->with('portal_state', 'pending')->with('success', 'Your KYC request has been submitted.');
    }

    public function logout(Request $request): RedirectResponse
    {
        foreach (['web', 'portal', 'admin'] as $guard) {
            Auth::guard($guard)->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }

    private function maskedEmail(?string $email): string
    {
        $normalized = $this->otpService->normalizeEmail((string) $email);

        if (! str_contains($normalized, '@')) {
            return $normalized;
        }

        [$local, $domain] = explode('@', $normalized, 2);
        $maskedLocal = strlen($local) <= 2
            ? substr($local, 0, 1).'***'
            : substr($local, 0, 2).'***'.substr($local, -1);

        return $maskedLocal.'@'.$domain;
    }

    private function remainingCooldown(Request $request): int
    {
        $lastSentAt = (int) $request->session()->get('portal_otp_last_sent_at', 0);
        $cooldown = (int) config('services.otp.resend_cooldown_seconds', 60);

        return max(0, $cooldown - (now()->timestamp - $lastSentAt));
    }
}
