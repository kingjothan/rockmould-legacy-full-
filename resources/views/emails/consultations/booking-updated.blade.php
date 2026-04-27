<x-emails.layouts.rockmould title="Your Consultation Details Have Been Updated" :company="$company">
    <p style="margin:0 0 18px;font-size:16px;line-height:1.8;color:#d3cec6;">
        Hello {{ $booking->full_name }},
    </p>
    <p style="margin:0 0 24px;font-size:16px;line-height:1.8;color:#d3cec6;">
        Your Rockmould consultation has been updated. Please review the latest details below.
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
    </table>

    @if ($includeMeetingLink && $booking->meeting_link)
        <div style="margin-top:24px;padding:22px;border:1px solid rgba(198,169,106,0.18);border-radius:18px;background:rgba(198,169,106,0.06);">
            <p style="margin:0 0 10px;font-size:12px;text-transform:uppercase;letter-spacing:0.18em;color:#c6a96a;">Meeting Link</p>
            <p style="margin:0 0 14px;font-size:15px;line-height:1.8;color:#d3cec6;">
                Your consultation link is now ready. Use the button below a few minutes before your session begins.
            </p>
            <a href="{{ $booking->meeting_link }}" style="display:inline-block;padding:14px 22px;border-radius:999px;background:#c6a96a;color:#111;text-decoration:none;font-size:13px;letter-spacing:0.18em;text-transform:uppercase;">Join Consultation</a>
        </div>
    @endif

    @if ($scheduleChanged || $statusChanged)
        <div style="margin-top:24px;padding:20px;border:1px solid rgba(255,255,255,0.08);border-radius:18px;background:rgba(255,255,255,0.03);">
            <p style="margin:0;font-size:15px;line-height:1.8;color:#d3cec6;">
                @if ($scheduleChanged)
                    Your schedule was updated. Please use the revised date and time above for your session.
                @elseif ($statusChanged)
                    Your consultation status has changed. If you need clarification, reply to this email or contact the Rockmould team directly.
                @endif
            </p>
        </div>
    @endif
</x-emails.layouts.rockmould>
