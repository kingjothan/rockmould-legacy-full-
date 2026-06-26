<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Models\NewsletterSubscriber;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        return view('admin.newsletter.index', [
            'company' => SiteData::company(),
            'subscribers' => NewsletterSubscriber::query()->where('status', 'subscribed')->latest('subscribed_at')->get(),
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'image_upload' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,webp,gif'],
            'video_url' => ['nullable', 'string', 'max:2048'],
            'video_upload' => ['nullable', 'file', 'max:204800', 'mimes:mp4,webm,mov,ogg'],
        ]);

        $subscribers = NewsletterSubscriber::query()->where('status', 'subscribed')->get();

        if ($subscribers->isEmpty()) {
            return back()->withErrors(['subject' => 'No active subscribers to send to.']);
        }

        $imageUrl = $data['image_url'] ?? null;
        if ($request->hasFile('image_upload')) {
            $path = $request->file('image_upload')->store('newsletter', 'public');
            $imageUrl = asset('storage/'.$path);
        }

        $videoUrl = $data['video_url'] ?? null;
        if ($request->hasFile('video_upload')) {
            $path = $request->file('video_upload')->store('newsletter', 'public');
            $videoUrl = asset('storage/'.$path);
        }

        $company = SiteData::company();
        $unsubscribeEmail = $company['email'] ?? config('mail.from.address', 'info@rockmould.com');
        $sent = 0;
        $failed = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterMail(
                    $data['subject'],
                    $data['body'],
                    $company,
                    $unsubscribeEmail,
                    $imageUrl,
                    $videoUrl,
                ));
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        Activity::log('newsletter.sent', "Newsletter \"{$data['subject']}\" sent to {$sent} subscribers.", $request->user(), null);

        $message = "Newsletter sent to {$sent} subscriber(s).";
        if ($failed > 0) {
            $message .= " {$failed} failed to send.";
        }

        return back()->with('success', $message);
    }

    public function destroy(Request $request, NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->update(['status' => 'unsubscribed']);
        Activity::log('newsletter.unsubscribed', "Subscriber {$subscriber->email} removed.", $request->user(), null);

        return back()->with('success', 'Subscriber removed.');
    }
}
