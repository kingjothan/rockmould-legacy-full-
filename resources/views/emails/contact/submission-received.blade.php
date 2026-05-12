<x-emails.layouts.rockmould title="New Website Message" :company="$company">
    <p style="margin:0 0 18px;font-size:16px;line-height:1.8;color:#d3cec6;">
        A new message has been submitted through the Rockmould contact form.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;border-spacing:0 10px;">
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Name</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $submission->full_name }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Email</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $submission->email }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Phone</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $submission->phone ?: 'Not provided' }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Department</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $submission->department ?: 'General Inquiry' }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Received</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ optional($submission->created_at)->format('l, F j, Y g:i A') }}</td>
        </tr>
    </table>

    <div style="margin-top:26px;padding:20px;border:1px solid rgba(255,255,255,0.08);border-radius:18px;background:rgba(255,255,255,0.03);">
        <p style="margin:0 0 10px;font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Message</p>
        <p style="margin:0;font-size:15px;line-height:1.8;color:#d3cec6;">{{ $submission->message }}</p>
    </div>

    <p style="margin:28px 0 0;">
        <a href="{{ $adminUrl }}" style="display:inline-block;padding:14px 22px;border-radius:999px;background:#c6a96a;color:#111;text-decoration:none;font-size:13px;letter-spacing:0.18em;text-transform:uppercase;">Open Form Submissions</a>
    </p>
</x-emails.layouts.rockmould>
