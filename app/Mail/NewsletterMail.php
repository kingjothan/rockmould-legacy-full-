<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $emailSubject,
        public string $body,
        public array $company,
        public string $unsubscribeEmail,
        public array $imageUrls = [],
        public array $videoUrls = [],
        public array $documentLinks = [],   // [['name'=>..., 'url'=>...], ...]  shown in email body
        public array $documentPaths = [],   // [['path'=>..., 'name'=>...], ...]  attached to email
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->emailSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.newsletter');
    }

    public function attachments(): array
    {
        return collect($this->documentPaths)
            ->map(fn ($doc) => Attachment::fromPath($doc['path'])->as($doc['name']))
            ->all();
    }
}
