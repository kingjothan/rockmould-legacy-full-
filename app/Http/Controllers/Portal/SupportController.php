<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(): View
    {
        $content = SiteData::portalPage('support');

        return view('portal.support', [
            'company' => SiteData::company(),
            'content' => $content,
            'assignments' => Auth::user()->assignments()->with(['project', 'unit'])->get(),
            'tickets' => Auth::user()->supportTickets()->with('assignment.project')->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_assignment_id' => ['nullable', 'integer', Rule::exists('project_assignments', 'id')->where(fn ($query) => $query->where('user_id', Auth::id()))],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        SupportTicket::query()->create([
            'user_id' => Auth::id(),
            'project_assignment_id' => $data['project_assignment_id'] ?? null,
            'full_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        return back()->with('success', 'Your support request has been submitted.');
    }
}
