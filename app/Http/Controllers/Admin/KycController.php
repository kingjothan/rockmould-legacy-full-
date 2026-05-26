<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycRequest;
use App\Models\User;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KycController extends Controller
{
    public function index(Request $request): View
    {
        $query = KycRequest::query()->with(['project', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('admin.kyc.index', [
            'company' => SiteData::company(),
            'requests' => $query->latest()->get(),
        ]);
    }

    public function update(Request $request, KycRequest $kycRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'admin_note' => ['nullable', 'string'],
        ]);

        $kycRequest->update([
            'status' => $data['status'],
            'admin_note' => $data['admin_note'] ?? null,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        if ($data['status'] === 'approved') {
            User::query()->updateOrCreate(
                ['email' => strtolower((string) $kycRequest->email)],
                [
                    'name' => $kycRequest->full_name,
                    'phone' => $kycRequest->phone,
                    'address' => $kycRequest->residential_address,
                    'status' => 'active',
                    'role' => 'client',
                    'title' => 'Client',
                    'department' => 'Clients',
                ],
            );
        }

        Activity::log('kyc.updated', 'KYC request reviewed.', $request->user(), $kycRequest, ['status' => $data['status']]);

        return back()->with('success', 'KYC request updated.');
    }
}
