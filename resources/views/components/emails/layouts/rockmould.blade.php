@props([
    'title',
    'company',
])

@php
    $appUrl = (string) config('app.url');
    $isLocalUrl = str_contains($appUrl, '127.0.0.1')
        || str_contains($appUrl, 'localhost')
        || str_contains($appUrl, '.test');
    $configuredLogoUrl = config('mail.rockmould.logo_url');
    $logoSource = $configuredLogoUrl ?: ($isLocalUrl ? null : asset('images/rockmould_logo_white.png'));
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background:#080808;color:#f5f1e8;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
        {{ $title }} from Rockmould Limited.
    </div>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#080808;padding:34px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:720px;background:#121212;border:1px solid #2c261a;border-radius:28px;overflow:hidden;box-shadow:0 28px 90px rgba(0,0,0,0.45);">
                    <tr>
                        <td style="padding:0;background:#070707;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:linear-gradient(135deg,#0b0b0b 0%,#16130e 58%,#080808 100%);">
                                <tr>
                                    <td style="padding:34px 36px 28px;border-bottom:1px solid #272018;">
                                        @if ($logoSource)
                                            <img src="{{ $logoSource }}" alt="Rockmould Limited" width="120" style="display:block;width:120px;max-width:120px;height:auto;border:0;outline:none;text-decoration:none;">
                                        @else
                                            <div style="display:inline-block;border:1px solid #c6a96a;padding:10px 14px;color:#fbf7ef;font-size:13px;line-height:1.1;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;">
                                                Rockmould<br>Limited
                                            </div>
                                        @endif
                                        <p style="margin:24px 0 0;font-size:11px;letter-spacing:0.28em;text-transform:uppercase;color:#c6a96a;font-weight:700;">Rockmould Limited</p>
                                        <h1 style="margin:12px 0 0;font-family:Georgia,'Times New Roman',serif;font-size:38px;line-height:1.08;font-weight:700;color:#fbf7ef;letter-spacing:-0.02em;">{{ $title }}</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 36px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 36px 34px;border-top:1px solid #272018;background:#0d0d0d;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="vertical-align:top;">
                                        <p style="margin:0 0 8px;font-size:11px;letter-spacing:0.22em;text-transform:uppercase;color:#c6a96a;font-weight:700;">Contact Desk</p>
                                        <p style="margin:0;font-size:13px;line-height:1.8;color:#bdb7ad;">
                                            {{ $company['name'] ?? 'Rockmould Limited' }}<br>
                                            {{ $company['address'] ?? '' }}<br>
                                            {{ $company['phone'] ?? '' }}{{ ! empty($company['phone']) && ! empty($company['email']) ? ' | ' : '' }}{{ $company['email'] ?? 'info@rockmould.com' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
