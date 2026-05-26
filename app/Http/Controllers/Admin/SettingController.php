<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'company' => SiteData::company(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string'],
            'hours' => ['required', 'string', 'max:255'],
            'support_phone' => ['nullable', 'string', 'max:50'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'public_copy_scale' => ['required', 'numeric', 'min:0.7', 'max:2.5'],
            'public_heading_scale' => ['required', 'numeric', 'min:0.7', 'max:2.5'],
            'portal_copy_scale' => ['required', 'numeric', 'min:0.7', 'max:2.5'],
            'portal_heading_scale' => ['required', 'numeric', 'min:0.7', 'max:2.5'],
            'admin_copy_scale' => ['required', 'numeric', 'min:0.7', 'max:2.5'],
            'admin_heading_scale' => ['required', 'numeric', 'min:0.7', 'max:2.5'],
        ]);

        Setting::query()->updateOrCreate(['key' => 'company'], ['value' => $data]);
        Activity::log('settings.updated', 'Company settings updated.', $request->user());

        return back()->with('success', 'Company settings updated.');
    }
}
