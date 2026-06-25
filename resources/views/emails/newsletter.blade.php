<x-emails.layouts.rockmould :title="$emailSubject" :company="$company">
    @foreach (explode("\n", $body) as $line)
        @if (trim($line) !== '')
            <p style="margin:0 0 18px;font-size:16px;line-height:1.85;color:#d8d1c5;">{{ trim($line) }}</p>
        @endif
    @endforeach

    <div style="margin-top:30px;padding-top:20px;border-top:1px solid #29231a;">
        <p style="margin:0;font-size:11px;line-height:1.6;color:#7a7368;">
            You're receiving this because you subscribed to updates from Rockmould Limited.
            To unsubscribe, reply to <a href="mailto:{{ $unsubscribeEmail }}" style="color:#c6a96a;">{{ $unsubscribeEmail }}</a> with "Unsubscribe" in the subject.
        </p>
    </div>
</x-emails.layouts.rockmould>
