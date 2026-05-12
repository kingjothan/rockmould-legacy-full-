<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\KycRequest;
use App\Models\User;
use App\Support\PublicStorage;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): View
    {
        $state = session('portal_state');

        if (! $state && $request->query('mode') === 'kyc') {
            $state = 'kyc';
        }

        return view('portal.login', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('login'),
            'state' => $state ?: 'login',
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
        ]);

        $normalized = $this->normalizePhone($data['phone']);
        $user = User::query()->where('role', 'client')->get()->first(function (User $user) use ($normalized) {
            return $this->normalizePhone((string) $user->phone) === $normalized;
        });

        if (! $user) {
            return back()->withInput()->with('portal_state', 'unrecognized');
        }

        if ($user->status !== 'active') {
            return back()->withErrors(['phone' => 'This client account is not active yet.'])->with('portal_state', 'pending');
        }

        Auth::login($user);
        $request->session()->regenerate();
        $user->forceFill(['last_active_at' => now()])->save();

        return redirect()->route('portal.dashboard');
    }

    public function kyc(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'residential_address' => ['required', 'string'],
            'project_of_interest' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'identification' => ['nullable', 'file', 'max:4096'],
        ]);

        $identification = $request->file('identification');
        $path = $identification ? PublicStorage::store($identification, 'kyc') : null;

        KycRequest::query()->create([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'residential_address' => $data['residential_address'],
            'project_of_interest' => $data['project_of_interest'] ?? null,
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

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            return '234'.substr($digits, 1);
        }

        if (! str_starts_with($digits, '234') && strlen($digits) === 10) {
            return '234'.$digits;
        }

        return $digits;
    }
}
