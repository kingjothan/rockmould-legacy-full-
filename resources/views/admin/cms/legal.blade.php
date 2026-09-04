<x-layouts.admin :company="$company" page-heading="Legal Pages CMS">
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-400">{{ session('success') }}</div>
    @endif

    @php
        $sizeOptions = [
            'text-xs'   => 'XS',
            'text-sm'   => 'SM',
            'text-base' => 'Base',
            'text-lg'   => 'LG',
            'text-xl'   => 'XL',
            'text-2xl'  => '2XL',
            'text-3xl'  => '3XL',
            'text-4xl'  => '4XL',
            'text-5xl'  => '5XL',
            'text-6xl'  => '6XL',
            'text-7xl'  => '7XL',
        ];

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
            <div class="border-b border-border pb-4">
                <p class="section-label">Legal</p>
                <h2 class="font-display text-3xl">Privacy Policy</h2>
                <p class="mt-2 text-sm text-muted-foreground">Edit the hero copy, font sizes, background image, and page sections. Edits appear live at <a href="{{ route('privacy') }}" target="_blank" class="text-primary hover:underline">/privacy</a></p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                {{-- Left: copy + media fields --}}
                <div class="min-w-0 space-y-4">
                    <div>
                        <label class="admin-label">Hero Subtitle</label>
                        <input name="privacy_hero_subtitle" value="{{ $legal['privacy_hero_subtitle'] ?? 'Legal' }}" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Hero Title</label>
                        <div class="flex gap-2">
                            <input name="privacy_hero_title" value="{{ $legal['privacy_hero_title'] ?? 'Privacy Policy' }}" class="input-shell flex-1">
                            <select name="privacy_title_size" class="input-shell w-24" title="Title font size">
                                @foreach ($sizeOptions as $val => $lbl)
                                    <option value="{{ $val }}" @selected(($legal['privacy_title_size'] ?? 'text-4xl') === $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Hero Description</label>
                        <div class="flex gap-2">
                            <textarea name="privacy_hero_description" rows="4" class="input-shell flex-1">{{ $legal['privacy_hero_description'] ?? 'How Rockmould collects, uses, and protects information shared across the website, portal, and client interactions.' }}</textarea>
                            <select name="privacy_description_size" class="input-shell w-24 self-start" title="Description font size">
                                @foreach ($sizeOptions as $val => $lbl)
                                    <option value="{{ $val }}" @selected(($legal['privacy_description_size'] ?? 'text-base') === $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
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

                {{-- Right: media preview --}}
                <div class="min-w-0 space-y-4">
                    <div class="overflow-hidden rounded-lg border border-border bg-card">
                        <div class="aspect-[16/10] bg-muted">
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
                        <div class="border-t border-border px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Hero Media</p>
                            <p class="mt-1 truncate text-sm text-muted-foreground">{{ $privacyMediaPath ?: 'Not set — using default image' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sections --}}
            <div class="border-t border-border pt-6">
                <div class="mb-3 flex items-center justify-between">
                    <label class="admin-label mb-0">Page Sections</label>
                    <button type="button" data-add-section="privacy" class="text-xs text-primary hover:underline">+ Add Section</button>
                </div>
                <div id="privacy-sections" class="space-y-3">
                    @foreach ($privacySections as $i => $section)
                    <div class="legal-section-row rounded-lg border border-border bg-background/40 p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="section-number text-xs font-medium text-muted-foreground">Section {{ $i + 1 }}</span>
                            <button type="button" data-remove-section class="text-xs text-destructive hover:underline">Remove</button>
                        </div>
                        <div style="display:grid;gap:0.75rem;grid-template-columns:1fr 2fr;">
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
            <div class="border-b border-border pb-4">
                <p class="section-label">Legal</p>
                <h2 class="font-display text-3xl">Terms of Service</h2>
                <p class="mt-2 text-sm text-muted-foreground">Edit the hero copy, font sizes, background image, and page sections. Edits appear live at <a href="{{ route('terms') }}" target="_blank" class="text-primary hover:underline">/terms</a></p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                {{-- Left: copy + media fields --}}
                <div class="min-w-0 space-y-4">
                    <div>
                        <label class="admin-label">Hero Subtitle</label>
                        <input name="terms_hero_subtitle" value="{{ $legal['terms_hero_subtitle'] ?? 'Legal' }}" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Hero Title</label>
                        <div class="flex gap-2">
                            <input name="terms_hero_title" value="{{ $legal['terms_hero_title'] ?? 'Terms of Service' }}" class="input-shell flex-1">
                            <select name="terms_title_size" class="input-shell w-24" title="Title font size">
                                @foreach ($sizeOptions as $val => $lbl)
                                    <option value="{{ $val }}" @selected(($legal['terms_title_size'] ?? 'text-4xl') === $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Hero Description</label>
                        <div class="flex gap-2">
                            <textarea name="terms_hero_description" rows="4" class="input-shell flex-1">{{ $legal['terms_hero_description'] ?? 'The core terms governing the use of the Rockmould website, portal, and client-facing digital services.' }}</textarea>
                            <select name="terms_description_size" class="input-shell w-24 self-start" title="Description font size">
                                @foreach ($sizeOptions as $val => $lbl)
                                    <option value="{{ $val }}" @selected(($legal['terms_description_size'] ?? 'text-base') === $val)>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
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

                {{-- Right: media preview --}}
                <div class="min-w-0 space-y-4">
                    <div class="overflow-hidden rounded-lg border border-border bg-card">
                        <div class="aspect-[16/10] bg-muted">
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
                        <div class="border-t border-border px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Hero Media</p>
                            <p class="mt-1 truncate text-sm text-muted-foreground">{{ $termsMediaPath ?: 'Not set — using default image' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sections --}}
            <div class="border-t border-border pt-6">
                <div class="mb-3 flex items-center justify-between">
                    <label class="admin-label mb-0">Page Sections</label>
                    <button type="button" data-add-section="terms" class="text-xs text-primary hover:underline">+ Add Section</button>
                </div>
                <div id="terms-sections" class="space-y-3">
                    @foreach ($termsSections as $i => $section)
                    <div class="legal-section-row rounded-lg border border-border bg-background/40 p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="section-number text-xs font-medium text-muted-foreground">Section {{ $i + 1 }}</span>
                            <button type="button" data-remove-section class="text-xs text-destructive hover:underline">Remove</button>
                        </div>
                        <div style="display:grid;gap:0.75rem;grid-template-columns:1fr 2fr;">
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
            <button type="submit" class="luxury-btn-primary">Save Legal Pages</button>
        </div>
    </form>

<script>
(function () {
    function makeRow(number) {
        var wrap = document.createElement('div');
        wrap.className = 'legal-section-row rounded-lg border border-border bg-background/40 p-4';

        var header = document.createElement('div');
        header.className = 'mb-3 flex items-center justify-between';
        var numLabel = document.createElement('span');
        numLabel.className = 'section-number text-xs font-medium text-muted-foreground';
        numLabel.textContent = 'Section ' + number;
        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.setAttribute('data-remove-section', '');
        removeBtn.className = 'text-xs text-destructive hover:underline';
        removeBtn.textContent = 'Remove';
        header.appendChild(numLabel);
        header.appendChild(removeBtn);

        var grid = document.createElement('div');
        grid.style.cssText = 'display:grid;gap:0.75rem;grid-template-columns:1fr 2fr;';

        var col1 = document.createElement('div');
        var lbl1 = document.createElement('label');
        lbl1.className = 'admin-label';
        lbl1.textContent = 'Heading';
        var inp = document.createElement('input');
        inp.className = 'input-shell w-full section-title';
        inp.placeholder = 'Section heading';
        col1.appendChild(lbl1);
        col1.appendChild(inp);

        var col2 = document.createElement('div');
        var lbl2 = document.createElement('label');
        lbl2.className = 'admin-label';
        lbl2.textContent = 'Body';
        var ta = document.createElement('textarea');
        ta.className = 'input-shell w-full section-body';
        ta.rows = 3;
        ta.placeholder = 'Section body text';
        col2.appendChild(lbl2);
        col2.appendChild(ta);

        grid.appendChild(col1);
        grid.appendChild(col2);
        wrap.appendChild(header);
        wrap.appendChild(grid);
        return wrap;
    }

    function renumber(container) {
        container.querySelectorAll('.section-number').forEach(function (el, i) {
            el.textContent = 'Section ' + (i + 1);
        });
    }

    function serializeSections(page) {
        var sections = [];
        document.querySelectorAll('#' + page + '-sections .legal-section-row').forEach(function (row) {
            var title = row.querySelector('.section-title').value.trim();
            var body  = row.querySelector('.section-body').value.trim();
            if (title || body) sections.push({ title: title, body: body });
        });
        document.getElementById(page + '-sections-json').value = JSON.stringify(sections);
    }

    document.addEventListener('click', function (e) {
        var addBtn = e.target.closest('[data-add-section]');
        if (addBtn) {
            var page = addBtn.getAttribute('data-add-section');
            var container = document.getElementById(page + '-sections');
            container.appendChild(makeRow(container.querySelectorAll('.legal-section-row').length + 1));
            renumber(container);
            return;
        }
        var removeBtn = e.target.closest('[data-remove-section]');
        if (removeBtn) {
            var row = removeBtn.closest('.legal-section-row');
            var container = row.parentElement;
            row.remove();
            renumber(container);
        }
    });

    document.getElementById('legal-form').addEventListener('submit', function () {
        serializeSections('privacy');
        serializeSections('terms');
    });
}());
</script>
</x-layouts.admin>
