@props([
    'title',
    'company',
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background:#0a0a0b;color:#f5f1e8;font-family:Georgia,serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#0a0a0b;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#121214;border:1px solid rgba(202,175,120,0.2);border-radius:24px;overflow:hidden;">
                    <tr>
                        <td style="padding:32px 32px 20px;border-bottom:1px solid rgba(255,255,255,0.08);">
                            <img src="{{ asset('images/rockmould_logo_white.png') }}" alt="Rockmould Limited" style="display:block;height:56px;width:auto;">
                            <p style="margin:22px 0 0;font-size:11px;letter-spacing:0.22em;text-transform:uppercase;color:#c6a96a;">Rockmould Limited</p>
                            <h1 style="margin:12px 0 0;font-size:34px;line-height:1.12;font-weight:600;color:#f7f3eb;">{{ $title }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 32px;border-top:1px solid rgba(255,255,255,0.08);">
                            <p style="margin:0;font-size:13px;line-height:1.7;color:#b5b0a8;">
                                {{ $company['name'] }}<br>
                                {{ $company['address'] }}<br>
                                {{ $company['phone'] }} | {{ $company['email'] }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
