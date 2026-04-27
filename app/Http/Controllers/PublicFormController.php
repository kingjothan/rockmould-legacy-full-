<?php

namespace App\Http\Controllers;

use App\Models\CareerOpening;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use App\Models\PartnershipInquiry;
use App\Models\Project;
use App\Support\Activity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicFormController extends Controller
{
    public function contact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $submission = ContactSubmission::query()->create($data + ['status' => 'new']);
        Activity::log('contact.created', 'New public contact submission received.', null, $submission);

        return back()->with('success', 'Your message has been sent. A Rockmould advisor will reach out shortly.');
    }

    public function newsletter(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::query()->firstOrCreate(
            ['email' => $data['email']],
            ['status' => 'subscribed', 'subscribed_at' => now()],
        );

        return back()->with('success', 'Thanks for subscribing to Rockmould updates.');
    }

    public function partnership(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'interest_type' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $inquiry = PartnershipInquiry::query()->create($data + ['status' => 'new']);
        Activity::log('partnership.created', 'New partnership inquiry received.', null, $inquiry);

        return back()->with('success', 'Your partnership inquiry has been received. Our team will follow up soon.');
    }

    public function projectInquiry(Request $request, Project $project): RedirectResponse
    {
        abort_unless($project->is_published, 404);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string'],
        ]);

        ContactSubmission::query()->create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'department' => 'Project Inquiry: '.$project->title,
            'message' => $data['message'],
            'status' => 'new',
        ]);

        return back()->with('success', 'Your project inquiry has been sent.');
    }

    public function career(Request $request, CareerOpening $careerOpening): RedirectResponse
    {
        abort_unless($careerOpening->is_open && ($careerOpening->published_at === null || $careerOpening->published_at->isPast()), 404);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string'],
        ]);

        ContactSubmission::query()->create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'department' => 'Career Application: '.$careerOpening->title,
            'message' => $data['message'],
            'status' => 'new',
        ]);

        return back()->with('success', 'Your expression of interest has been recorded for this role.');
    }
}
