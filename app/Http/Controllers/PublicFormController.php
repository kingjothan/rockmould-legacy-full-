<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionReceivedMail;
use App\Models\CareerOpening;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use App\Models\PartnershipInquiry;
use App\Models\Project;
use App\Models\Testimonial;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        $this->notifyContactTeam($submission);

        return back()->with('success', 'Your message has been sent. A Rockmould advisor will reach out shortly.');
    }

    public function newsletter(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::query()->updateOrCreate(
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

        $submission = ContactSubmission::query()->create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'department' => 'Project Inquiry: '.$project->title,
            'message' => $data['message'],
            'status' => 'new',
        ]);

        Activity::log('contact.created', 'New public project inquiry received.', null, $submission);
        $this->notifyContactTeam($submission);

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

        $submission = ContactSubmission::query()->create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'department' => 'Career Application: '.$careerOpening->title,
            'message' => $data['message'],
            'status' => 'new',
        ]);

        Activity::log('contact.created', 'New public career expression of interest received.', null, $submission);
        $this->notifyContactTeam($submission);

        return back()->with('success', 'Your expression of interest has been recorded for this role.');
    }

    public function review(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:2000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        Testimonial::query()->create($data + [
            'is_visible' => false,
            'sort_order' => 999,
        ]);

        Activity::log('testimonial.created', 'New public review submitted.', null, null);

        return back()->with('success', 'Thank you for your review! It will appear on the site once approved by our team.');
    }

    private function notifyContactTeam(ContactSubmission $submission): void
    {
        Mail::to(config('mail.rockmould.contact_to', 'info@rockmould.com'))->send(new ContactSubmissionReceivedMail(
            $submission,
            SiteData::company(),
            route('admin.forms.index'),
        ));
    }
}
