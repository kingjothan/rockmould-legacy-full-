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
use Illuminate\Support\Facades\Storage;
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
            'send_to' => ['required', 'string', 'in:all,selected'],
            'recipients' => ['nullable', 'array'],
            'recipients.*' => ['integer', 'exists:newsletter_subscribers,id'],
            'image_urls' => ['nullable', 'string'],
            'image_uploads' => ['nullable', 'array'],
            'image_uploads.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,webp,gif'],
            'video_urls' => ['nullable', 'string'],
            'video_uploads' => ['nullable', 'array'],
            'video_uploads.*' => ['file', 'max:204800', 'mimes:mp4,webm,mov,ogg'],
            'document_urls' => ['nullable', 'string'],
            'document_uploads' => ['nullable', 'array'],
            'document_uploads.*' => ['file', 'max:51200', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx'],
        ]);

        if ($data['send_to'] === 'selected' && !empty($data['recipients'])) {
            $subscribers = NewsletterSubscriber::query()->whereIn('id', $data['recipients'])->where('status', 'subscribed')->get();
        } else {
            $subscribers = NewsletterSubscriber::query()->where('status', 'subscribed')->get();
        }

        if ($subscribers->isEmpty()) {
            return back()->withErrors(['subject' => 'No active subscribers to send to.']);
        }

        $imageUrls = [];
        if (!empty($data['image_urls'])) {
            foreach (preg_split('/\r?\n/', $data['image_urls']) as $url) {
                $url = trim($url);
                if ($url) $imageUrls[] = $url;
            }
        }
        // Use site_public disk so files land in public_html/storage (directly web-accessible on cPanel)
        $disk = 'site_public';

        foreach ($request->file('image_uploads', []) as $file) {
            $path = $file->store('newsletter', $disk);
            $imageUrls[] = asset('storage/'.$path);
        }

        $videoUrls = [];
        if (!empty($data['video_urls'])) {
            foreach (preg_split('/\r?\n/', $data['video_urls']) as $url) {
                $url = trim($url);
                if ($url) $videoUrls[] = $url;
            }
        }
        foreach ($request->file('video_uploads', []) as $file) {
            $path = $file->store('newsletter', $disk);
            $videoUrls[] = asset('storage/'.$path);
        }

        // Documents: attached to email AND shown as download buttons in email body
        $documentLinks = [];
        $documentPaths = [];
        if (!empty($data['document_urls'])) {
            foreach (preg_split('/\r?\n/', $data['document_urls']) as $url) {
                $url = trim($url);
                if ($url) {
                    $documentLinks[] = ['name' => basename(parse_url($url, PHP_URL_PATH)) ?: $url, 'url' => $url];
                }
            }
        }
        foreach ($request->file('document_uploads', []) as $file) {
            $originalName = $file->getClientOriginalName();
            $path = $file->storeAs('newsletter/docs', $originalName, $disk);
            $documentPaths[] = ['path' => Storage::disk($disk)->path('newsletter/docs/'.$originalName), 'name' => $originalName];
            $documentLinks[] = ['name' => $originalName, 'url' => asset('storage/newsletter/docs/'.rawurlencode($originalName))];
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
                    $imageUrls,
                    $videoUrls,
                    $documentLinks,
                    $documentPaths,
                ));
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                \Log::error('Newsletter send failed to '.$subscriber->email.': '.$e->getMessage());
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
