<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('portal.profile', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('profile'),
            'user' => Auth::user()->load('assignments.project'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
        ]);

        Auth::user()->update($data);

        return back()->with('success', 'Your profile details have been updated.');
    }
}
