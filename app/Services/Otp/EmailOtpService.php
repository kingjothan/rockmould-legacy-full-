<?php

namespace App\Services\Otp;

use App\Mail\ClientPortalOtpMail;
use App\Models\ClientOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailOtpService
{
    public function send(User $client, Request $request): ClientOtp
    {
        $code = $this->generateCode();
        $email = $this->normalizeEmail((string) $client->email);
        ClientOtp::query()
            ->where('user_id', $client->id)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now(), 'delivery_status' => 'superseded']);

        $otp = ClientOtp::query()->create([
            'user_id' => $client->id,
            'phone' => $email,
            'email' => $email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addYears(5),
            'request_ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 1000, ''),
            'delivery_status' => 'pending',
            'delivery_channel' => 'email',
            'gateway' => config('mail.default'),
        ]);

        Mail::to($email)->send(new ClientPortalOtpMail($client, $code));

        $otp->update([
            'sent_at' => now(),
            'delivery_status' => 'sent',
        ]);

        return $otp->fresh();
    }

    public function normalizeEmail(string $email): string
    {
        return Str::of($email)->trim()->lower()->toString();
    }

    private function generateCode(): string
    {
        if (app()->environment('testing')) {
            return (string) config('services.otp.testing_code', '123456');
        }

        return (string) random_int(100000, 999999);
    }
}
