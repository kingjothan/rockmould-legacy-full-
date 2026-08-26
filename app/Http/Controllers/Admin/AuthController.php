<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.login', [
            'company' => SiteData::company(),
            'loginContent' => SiteData::adminLoginContent(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([...$credentials, 'status' => 'active'], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid admin credentials.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! $request->user()?->isAdminRole()) {
            $this->logoutFromAllGuards($request);

            return back()->withErrors(['email' => 'This account does not have admin access.']);
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->logoutFromAllGuards($request);

        return redirect()->route('home');
    }

    private function logoutFromAllGuards(Request $request): void
    {
        foreach (['web', 'admin', 'portal'] as $guard) {
            Auth::guard($guard)->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
