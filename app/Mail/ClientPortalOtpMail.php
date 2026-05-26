<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Support\SiteData;

class ClientPortalOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $client,
        public readonly string $code,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Your Rockmould portal verification code')
            ->view('emails.portal.otp')
            ->with([
                'client' => $this->client,
                'code' => $this->code,
                'company' => SiteData::company(),
            ]);
    }
}
