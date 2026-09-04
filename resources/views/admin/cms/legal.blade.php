<x-layouts.admin :company="$company" page-heading="Legal Pages CMS">
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-400">{{ session('success') }}</div>
    @endif

    @php
        $privacySections = $legal['privacy_sections'] ?? [
            ['title' => 'Data Collection', 'body' => 'Rockmould Limited collects contact details, project inquiry information, client portal records, uploaded documents, and support communications to deliver services and maintain operational records.'],
            ['title' => 'How We Use Your Data', 'body' => 'Information provided through contact forms, partnerships, newsletters, KYC requests, or the client portal may be used for relationship management, project reporting, account review, and compliance workflows.'],
            ['title' => 'Data Protection', 'body' => 'We do not sell personal information. Access to sensitive records is restricted to authorized Rockmould personnel who require it for legitimate operational, finance, project, or support purposes.'],
            ['title' => 'Contact & Corrections', 'body' => 'If you need corrections, account review, or record clarification, contact us or use the portal support channel.'],
        ];
        $termsSections = $legal['terms_sections'] ?? [
            ['title' => 'Website Use', 'body' => 'Content on this website is provided for general information, project marketing, and relationship management purposes. Public site materials do not constitute a final commercial offer unless confirmed by Rockmould in writing.'],
            ['title' => 'Client Portal Access', 'body' => 'Client portal access is intended only for recognized clients and approved users. Misuse of project data, documents, media, or support channels is prohibited.'],
            ['title' => 'Project Information', 'body' => 'Project timelines, visuals, payment schedules, and availability may change. Official transaction documents, agreements, and internal approvals govern final commercial terms.'],
            ['title' => 'Acceptance of Terms', 'body' => 'By using this website or portal, you agree to engage with Rockmould lawfully and in good faith. For questions about these terms, contact us.'],
        ];

        $privacyMediaPath = $legal['privacy_hero_media_path'] ?? null;
        $privacyMediaUrl  = $privacyMediaPath ? \App\Support\AssetPath::url($privacyMediaPath) : null;
        $privacyIsVideo   = \App\Support\AssetPath::isVideo($privacyMediaPath);

        $termsMediaPath = $legal['terms_hero_media_path'] ?? null;
        $termsMediaUrl  = $termsMediaPath ? \App\Support\AssetPath::url($termsMediaPath) : null;
        $termsIsVideo   = \App\Support\AssetPath::isVideo($termsMediaPath);
    @endphp

    <form action="{{ route('admin.cms.legal.update') }}" method="POST" enctype="multipart/form-data" class="space-y-10" id="legal-form">
        @csrf
        @method('PUT')

        {{-- ═══ Privacy Policy ═══ --}}
        <div class="luxury-card space-y-6">
            <div class="flex items-center justify-between border-b border-border pb-4">
                <div>
                    <h2 class="text-base font-semibold">Privacy Policy</h2>
                    <p class="mt-1 text-xs text-muted-foreground">Edits appear live at <a href="{{ route('privacy') }}" target="_blank" class="text-primary hover:underline">/privacy</a></p>
                </div>
            </div>

            {{-- Hero media --}}
            <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                <div class="space-y-4">
                    <div>
                        <label class="admin-label">Page Description (shown under the hero)</label>
                        <textarea name="privacy_hero_description" rows="2" class="input-shell w-full">{{ $legal['privacy_hero_description'] ?? 'How Rockmould collects, uses, and protects information shared across the website, portal, and client interactions.' }}</textarea>
                    </div>
                    <div>
                        <label class="admin-label">Hero Background Link or Path</label>
                        <input name="privacy_hero_media_path" value="{{ $privacyMediaPath }}" class="input-shell w-full" placeholder="https://cdn.example.com/privacy-hero.jpg">
                        <p class="mt-1 text-xs text-muted-foreground">Paste a CDN/direct image or video URL, or upload a file below.</p>
                    </div>
                    <div>
                        <label class="admin-label">Upload Hero Media</label>
                        <input type="file" name="privacy_hero_media_upload" accept="image/*,video/*" class="input-shell w-full">
                        <p class="mt-1 text-xs text-muted-foreground">Image or video. Recommended: 1920×1080 or better.</p>
                        <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                            <input type="checkbox" name="remove_privacy_hero_media" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Remove current hero media
                        </label>
                    </div>
                </div>
                <div class="overflow-hidden rounded-lg border border-border">
                    <div class="relative h-40 bg-muted">
                        @if ($privacyMediaUrl)
                            @if ($privacyIsVideo)
                                <video src="{{ $privacyMediaUrl }}" class="h-full w-full object-cover" muted autoplay loop playsinline></video>
                            @else
                                <img src="{{ $privacyMediaUrl }}" alt="Privacy hero" class="h-full w-full object-cover">
                            @endif
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-muted-foreground">No hero media set</div>
                        @endif
                    </div>
                    <div class="border-t border-border px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Hero Media</p>
                        <p class="mt-1 truncate text-xs text-muted-foreground">{{ $privacyMediaPath ?: 'Not set — using default image' }}</p>
                    </div>
                </div>
            </div>

            {{-- Sections --}}
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <label class="admin-label mb-0">Sections</label>
                    <button type="button" onclick="addSection('privacy')" class="luxury-btn-outline text-xs">+ Add Section</button>
                </div>
                <div id="privacy-sections" class="space-y-3">
                    @foreach ($privacySections as $i => $section)
                    <div class="legal-section-row rounded-lg border border-border bg-background/40 p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="section-number text-xs font-medium text-muted-foreground">Section {{ $i + 1 }}</span>
                            <button type="button" onclick="removeSection(this)" class="rounded px-2 py-0.5 text-xs text-destructive ring-1 ring-destructive/30 hover:bg-destructive/10">Remove</button>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-[1fr_2fr]">
                            <div>
                                <label class="admin-label">Heading</label>
                                <input class="input-shell w-full section-title" value="{{ $section['title'] }}" placeholder="Section heading">
                            </div>
                            <div>
                                <label class="admin-label">Body</label>
                                <textarea class="input-shell w-full section-body" rows="3" placeholder="Section body text">{{ $section['body'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="privacy_sections_json" id="privacy-sections-json">
            </div>
        </div>

        {{-- ═══ Terms of Service ═══ --}}
        <div class="luxury-card space-y-6">
            <div class="flex items-center justify-between border-b border-border pb-4">
                <div>
                    <h2 class="text-base font-semibold">Terms of Service</h2>
                    <p class="mt-1 text-xs text-muted-foreground">Edits appear live at <a href="{{ route('terms') }}" target="_blank" class="text-primary hover:underline">/terms</a></p>
                </div>
            </div>

            {{-- Hero media --}}
            <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                <div class="space-y-4">
                    <div>
                        <label class="admin-label">Page Description (shown under the hero)</label>
                        <textarea name="terms_hero_description" rows="2" class="input-shell w-full">{{ $legal['terms_hero_description'] ?? 'The core terms governing the use of the Rockmould website, portal, and client-facing digital services.' }}</textarea>
                    </div>
                    <div>
                        <label class="admin-label">Hero Background Link or Path</label>
                        <input name="terms_hero_media_path" value="{{ $termsMediaPath }}" class="input-shell w-full" placeholder="https://cdn.example.com/terms-hero.jpg">
                        <p class="mt-1 text-xs text-muted-foreground">Paste a CDN/direct image or video URL, or upload a file below.</p>
                    </div>
                    <div>
                        <label class="admin-label">Upload Hero Media</label>
                        <input type="file" name="terms_hero_media_upload" accept="image/*,video/*" class="input-shell w-full">
                        <p class="mt-1 text-xs text-muted-foreground">Image or video. Recommended: 1920×1080 or better.</p>
                        <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                            <input type="checkbox" name="remove_terms_hero_media" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Remove current hero media
                        </label>
                    </div>
                </div>
                <div class="overflow-hidden rounded-lg border border-border">
                    <div class="relative h-40 bg-muted">
                        @if ($termsMediaUrl)
                            @if ($termsIsVideo)
                                <video src="{{ $termsMediaUrl }}" class="h-full w-full object-cover" muted autoplay loop playsinline></video>
                            @else
                                <img src="{{ $termsMediaUrl }}" alt="Terms hero" class="h-full w-full object-cover">
                            @endif
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-muted-foreground">No hero media set</div>
                        @endif
                    </div>
                    <div class="border-t border-border px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Hero Media</p>
                        <p class="mt-1 truncate text-xs text-muted-foreground">{{ $termsMediaPath ?: 'Not set — using default image' }}</p>
                    </div>
                </div>
            </div>

            {{-- Sections --}}
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <label class="admin-label mb-0">Sections</label>
                    <button type="button" onclick="addSection('terms')" class="luxury-btn-outline text-xs">+ Add Section</button>
                </div>
                <div id="terms-sections" class="space-y-3">
                    @foreach ($termsSections as $i => $section)
                    <div class="legal-section-row rounded-lg border border-border bg-background/40 p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="section-number text-xs font-medium text-muted-foreground">Section {{ $i + 1 }}</span>
                            <button type="button" onclick="removeSection(this)" class="rounded px-2 py-0.5 text-xs text-destructive ring-1 ring-destructive/30 hover:bg-destructive/10">Remove</button>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-[1fr_2fr]">
                            <div>
                                <label class="admin-label">Heading</label>
                                <input class="input-shell w-full section-title" value="{{ $section['title'] }}" placeholder="Section heading">
                            </div>
                            <div>
                                <label class="admin-label">Body</label>
                                <textarea class="input-shell w-full section-body" rows="3" placeholder="Section body text">{{ $section['body'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="terms_sections_json" id="terms-sections-json">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="luxury-btn">Save Legal Pages</button>
        </div>
    </form>

<script>
function addSection(page) {
    var container = document.getElementById(page + '-sections');
    var count = container.querySelectorAll('.legal-section-row').length + 1;
    var row = document.createElement('div');
    row.className = 'legal-section-row rounded-lg border border-border bg-background/40 p-4';
    row.innerHTML =
        '<div class="mb-3 flex items-center justify-between">'
        + '<span class="section-number text-xs font-medium text-muted-foreground">Section ' + count + '</span>'
        + '<button type="button" onclick="removeSection(this)" class="rounded px-2 py-0.5 text-xs text-destructive ring-1 ring-destructive/30 hover:bg-destructive/10">Remove</button>'
        + '</div>'
        + '<div class="grid gap-3 sm:grid-cols-[1fr_2fr]">'
        + '<div><label class="admin-label">Heading</label><input class="input-shell w-full section-title" value="" placeholder="Section heading"></div>'
        + '<div><label class="admin-label">Body</label><textarea class="input-shell w-full section-body" rows="3" placeholder="Section body text"></textarea></div>'
        + '</div>';
    container.appendChild(row);
    renumber(container);
}

function removeSection(btn) {
    var row = btn.closest('.legal-section-row');
    var container = row.parentElement;
    row.remove();
    renumber(container);
}

function renumber(container) {
    container.querySelectorAll('.section-number').forEach(function(el, i) {
        el.textContent = 'Section ' + (i + 1);
    });
}

function serializeSections(page) {
    var sections = [];
    document.querySelectorAll('#' + page + '-sections .legal-section-row').forEach(function(row) {
        var title = row.querySelector('.section-title').value.trim();
        var body  = row.querySelector('.section-body').value.trim();
        if (title || body) sections.push({ title: title, body: body });
    });
    document.getElementById(page + '-sections-json').value = JSON.stringify(sections);
}

document.getElementById('legal-form').addEventListener('submit', function() {
    serializeSections('privacy');
    serializeSections('terms');
});
</script>
</x-layouts.admin>
