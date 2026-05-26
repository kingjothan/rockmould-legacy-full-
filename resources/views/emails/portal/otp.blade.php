<x-emails.layouts.rockmould title="Portal verification code" :company="$company">
    <p style="margin:0 0 18px;font-size:16px;line-height:1.8;color:#d8d1c5;">Hello {{ $client->name }},</p>

    <p style="margin:0 0 18px;font-size:16px;line-height:1.8;color:#d8d1c5;">Use the code below to access your Rockmould client portal.</p>

    <p style="font-size:28px;letter-spacing:8px;font-weight:700;margin:24px 0;color:#fbf7ef;">{{ $code }}</p>

    <p style="margin:0;font-size:14px;line-height:1.8;color:#bdb7ad;">If you did not request this code, you can safely ignore this email.</p>
</x-emails.layouts.rockmould>
