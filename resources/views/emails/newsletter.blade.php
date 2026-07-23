<x-emails.layouts.rockmould :title="$emailSubject" :company="$company">
    @foreach (explode("\n", $body) as $line)
        @if (trim($line) !== '')
            <p style="margin:0 0 18px;font-size:16px;line-height:1.85;color:#d8d1c5;">{{ trim($line) }}</p>
        @endif
    @endforeach

    @foreach ($imageUrls as $imgUrl)
        <div style="margin:20px 0;border-radius:12px;overflow:hidden;border:1px solid #29231a;">
            <img src="{{ $imgUrl }}" alt="Newsletter image" style="display:block;width:100%;max-width:100%;height:auto;border:0;">
        </div>
    @endforeach

    @foreach ($videoUrls as $vidUrl)
        @php
            $isYouTube = preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $vidUrl, $ytMatch);
            $isDirectVideo = preg_match('/\.(mp4|webm|mov|ogg)(\?|$)/i', $vidUrl);
        @endphp
        <div style="margin:20px 0;text-align:center;">
            @if ($isYouTube)
                <a href="{{ $vidUrl }}" target="_blank" style="display:block;position:relative;border-radius:12px;overflow:hidden;border:1px solid #29231a;">
                    <img src="https://img.youtube.com/vi/{{ $ytMatch[1] }}/maxresdefault.jpg" alt="Watch video" style="display:block;width:100%;max-width:100%;height:auto;border:0;">
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:64px;height:64px;background:rgba(0,0,0,0.7);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <div style="width:0;height:0;border-top:12px solid transparent;border-bottom:12px solid transparent;border-left:20px solid #fff;margin-left:4px;"></div>
                    </div>
                </a>
            @elseif ($isDirectVideo)
                <div style="border-radius:12px;overflow:hidden;border:1px solid #29231a;">
                    <video controls style="display:block;width:100%;max-width:100%;height:auto;">
                        <source src="{{ $vidUrl }}">
                    </video>
                </div>
            @else
                <a href="{{ $vidUrl }}" target="_blank" style="display:inline-block;padding:15px 28px;border-radius:999px;background:#c6a96a;color:#111;text-decoration:none;font-size:12px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Watch Video</a>
            @endif
        </div>
    @endforeach

    @if (!empty($documentLinks))
        <div style="margin:28px 0;padding:20px 24px;background:#1a1510;border:1px solid #3a2f1e;border-radius:12px;">
            <p style="margin:0 0 14px;font-size:11px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#c6a96a;">Attachments &amp; Documents</p>
            @foreach ($documentLinks as $doc)
                @php
                    $ext = strtolower(pathinfo($doc['name'], PATHINFO_EXTENSION));
                    $icon = match($ext) {
                        'pdf'  => '📄',
                        'doc','docx' => '📝',
                        'xls','xlsx' => '📊',
                        'ppt','pptx' => '📑',
                        default      => '📎',
                    };
                @endphp
                <a href="{{ $doc['url'] }}" target="_blank"
                   style="display:flex;align-items:center;gap:12px;padding:10px 14px;margin-bottom:8px;background:#221c13;border:1px solid #3a2f1e;border-radius:8px;text-decoration:none;color:#d8d1c5;font-size:13px;line-height:1.4;">
                    <span style="font-size:20px;flex-shrink:0;">{{ $icon }}</span>
                    <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $doc['name'] }}</span>
                    <span style="flex-shrink:0;font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#c6a96a;padding:4px 10px;border:1px solid #c6a96a;border-radius:999px;">Download</span>
                </a>
            @endforeach
            <p style="margin:10px 0 0;font-size:11px;color:#7a7368;">Documents are also attached directly to this email for offline access.</p>
        </div>
    @endif

    <div style="margin-top:30px;padding-top:20px;border-top:1px solid #29231a;">
        <p style="margin:0;font-size:11px;line-height:1.6;color:#7a7368;">
            You're receiving this because you subscribed to updates from Rockmould Limited.
            To unsubscribe, reply to <a href="mailto:{{ $unsubscribeEmail }}" style="color:#c6a96a;">{{ $unsubscribeEmail }}</a> with "Unsubscribe" in the subject.
        </p>
    </div>
</x-emails.layouts.rockmould>
