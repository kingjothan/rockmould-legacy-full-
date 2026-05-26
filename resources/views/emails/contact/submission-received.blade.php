<x-emails.layouts.rockmould title="New Website Message" :company="$company">
    <p style="margin:0 0 24px;font-size:16px;line-height:1.8;color:#d8d1c5;">
        A visitor has sent Rockmould a message from the public website. The details are below for quick follow-up.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;border-spacing:0 12px;">
        <tr>
            <td style="padding:18px 20px;border:1px solid #29231a;border-radius:18px;background:#171717;">
                <p style="margin:0 0 6px;font-size:10px;text-transform:uppercase;letter-spacing:0.22em;color:#c6a96a;font-weight:700;">Client Name</p>
                <p style="margin:0;font-size:18px;line-height:1.4;color:#fbf7ef;font-weight:700;">{{ $submission->full_name }}</p>
            </td>
        </tr>
    </table>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:4px;border-collapse:collapse;border:1px solid #29231a;border-radius:18px;overflow:hidden;background:#111;">
        <tr>
            <td width="38%" style="padding:15px 18px;border-bottom:1px solid #242018;font-size:11px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;font-weight:700;">Email</td>
            <td style="padding:15px 18px;border-bottom:1px solid #242018;font-size:15px;color:#f7f3eb;">
                <a href="mailto:{{ $submission->email }}" style="color:#f7f3eb;text-decoration:none;">{{ $submission->email }}</a>
            </td>
        </tr>
        <tr>
            <td width="38%" style="padding:15px 18px;border-bottom:1px solid #242018;font-size:11px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;font-weight:700;">Phone</td>
            <td style="padding:15px 18px;border-bottom:1px solid #242018;font-size:15px;color:#f7f3eb;">{{ $submission->phone ?: 'Not provided' }}</td>
        </tr>
        <tr>
            <td width="38%" style="padding:15px 18px;border-bottom:1px solid #242018;font-size:11px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;font-weight:700;">Department</td>
            <td style="padding:15px 18px;border-bottom:1px solid #242018;font-size:15px;color:#f7f3eb;">{{ $submission->department ?: 'General Inquiry' }}</td>
        </tr>
        <tr>
            <td width="38%" style="padding:15px 18px;font-size:11px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;font-weight:700;">Received</td>
            <td style="padding:15px 18px;font-size:15px;color:#f7f3eb;">{{ optional($submission->created_at)->format('l, F j, Y g:i A') }}</td>
        </tr>
    </table>

    <div style="margin-top:26px;padding:22px;border:1px solid #29231a;border-radius:20px;background:#171717;">
        <p style="margin:0 0 12px;font-size:11px;text-transform:uppercase;letter-spacing:0.22em;color:#c6a96a;font-weight:700;">Submitted Message</p>
        <p style="margin:0;font-size:15px;line-height:1.85;color:#d8d1c5;">{{ $submission->message }}</p>
    </div>

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:30px;">
        <tr>
            <td style="padding-right:12px;padding-bottom:12px;">
                <a href="{{ $adminUrl }}" style="display:inline-block;padding:15px 22px;border-radius:999px;background:#c6a96a;color:#111;text-decoration:none;font-size:12px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Open Admin</a>
            </td>
            <td style="padding-bottom:12px;">
                <a href="mailto:{{ $submission->email }}" style="display:inline-block;padding:14px 22px;border-radius:999px;border:1px solid #c6a96a;color:#c6a96a;text-decoration:none;font-size:12px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Reply Directly</a>
            </td>
        </tr>
    </table>
</x-emails.layouts.rockmould>
