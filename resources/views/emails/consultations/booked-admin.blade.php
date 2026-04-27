<x-emails.layouts.rockmould title="A New Consultation Request Has Arrived" :company="$company">
    <p style="margin:0 0 18px;font-size:16px;line-height:1.8;color:#d3cec6;">
        A new consultation request has been submitted on the Rockmould website.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;border-spacing:0 10px;">
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Client</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->full_name }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Email</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->email }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Phone</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->phone }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Consultation Type</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->consultation_type_label }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Schedule</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->formattedDate('l, F j, Y') }} | {{ $booking->formattedTimeRange() }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Subject</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->subject }}</td>
        </tr>
    </table>

    <div style="margin-top:26px;padding:20px;border:1px solid rgba(255,255,255,0.08);border-radius:18px;background:rgba(255,255,255,0.03);">
        <p style="margin:0 0 10px;font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Client Message</p>
        <p style="margin:0;font-size:15px;line-height:1.8;color:#d3cec6;">{{ $booking->message }}</p>
    </div>

    <p style="margin:28px 0 0;">
        <a href="{{ $adminUrl }}" style="display:inline-block;padding:14px 22px;border-radius:999px;background:#c6a96a;color:#111;text-decoration:none;font-size:13px;letter-spacing:0.18em;text-transform:uppercase;">Open in Admin</a>
    </p>
</x-emails.layouts.rockmould>
