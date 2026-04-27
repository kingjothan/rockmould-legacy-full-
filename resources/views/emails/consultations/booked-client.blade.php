<x-emails.layouts.rockmould title="Your Consultation Request Has Been Received" :company="$company">
    <p style="margin:0 0 18px;font-size:16px;line-height:1.8;color:#d3cec6;">
        Hello {{ $booking->full_name }},
    </p>
    <p style="margin:0 0 24px;font-size:16px;line-height:1.8;color:#d3cec6;">
        Thank you for scheduling a consultation with Rockmould. Your request has been received and is now awaiting confirmation from our team.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;border-spacing:0 10px;">
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Consultation Type</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->consultation_type_label }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Date</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->formattedDate('l, F j, Y') }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Time</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->formattedTimeRange() }} ({{ $booking->timezone }})</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Status</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->status_label }}</td>
        </tr>
        <tr>
            <td style="font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Reference</td>
            <td style="font-size:16px;color:#f7f3eb;">{{ $booking->reference }}</td>
        </tr>
    </table>

    <div style="margin-top:28px;padding:20px;border:1px solid rgba(198,169,106,0.18);border-radius:18px;background:rgba(198,169,106,0.06);">
        <p style="margin:0;font-size:15px;line-height:1.8;color:#d3cec6;">
            Next steps: a Rockmould team member will review your request, confirm the session internally, and send you any final meeting details if a virtual link is required.
        </p>
    </div>
</x-emails.layouts.rockmould>
