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
    @endphp

    <form action="{{ route('admin.cms.legal.update') }}" method="POST" class="space-y-10" id="legal-form">
        @csrf
        @method('PUT')

        {{-- Privacy Policy --}}
        <div class="luxury-card space-y-6">
            <div class="flex items-center justify-between border-b border-border pb-4">
                <div>
                    <h2 class="text-base font-semibold">Privacy Policy</h2>
                    <p class="mt-1 text-xs text-muted-foreground">Edits appear live at <a href="{{ route('privacy') }}" target="_blank" class="text-primary hover:underline">/privacy</a></p>
                </div>
            </div>

            <div>
                <label class="admin-label">Page Description (shown under the hero)</label>
                <textarea name="privacy_hero_description" rows="2" class="input-shell w-full">{{ $legal['privacy_hero_description'] ?? 'How Rockmould collects, uses, and protects information shared across the website, portal, and client interactions.' }}</textarea>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <label class="admin-label mb-0">Sections</label>
                    <button type="button" onclick="addSection('privacy')" class="luxury-btn-outline text-xs">+ Add Section</button>
                </div>
                <div id="privacy-sections" class="space-y-3">
                    @foreach ($privacySections as $i => $section)
                    <div class="legal-section-row rounded-lg border border-border bg-background/40 p-4" data-page="privacy">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-xs font-medium text-muted-foreground">Section {{ $i + 1 }}</span>
                            <button type="button" onclick="removeSection(this)" class="text-xs text-destructive hover:underline">Remove</button>
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

        {{-- Terms of Service --}}
        <div class="luxury-card space-y-6">
            <div class="flex items-center justify-between border-b border-border pb-4">
                <div>
                    <h2 class="text-base font-semibold">Terms of Service</h2>
                    <p class="mt-1 text-xs text-muted-foreground">Edits appear live at <a href="{{ route('terms') }}" target="_blank" class="text-primary hover:underline">/terms</a></p>
                </div>
            </div>

            <div>
                <label class="admin-label">Page Description (shown under the hero)</label>
                <textarea name="terms_hero_description" rows="2" class="input-shell w-full">{{ $legal['terms_hero_description'] ?? 'The core terms governing the use of the Rockmould website, portal, and client-facing digital services.' }}</textarea>
            </div>

            <div>
                <div class="mb-3 flex items-center justify-between">
                    <label class="admin-label mb-0">Sections</label>
                    <button type="button" onclick="addSection('terms')" class="luxury-btn-outline text-xs">+ Add Section</button>
                </div>
                <div id="terms-sections" class="space-y-3">
                    @foreach ($termsSections as $i => $section)
                    <div class="legal-section-row rounded-lg border border-border bg-background/40 p-4" data-page="terms">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="text-xs font-medium text-muted-foreground">Section {{ $i + 1 }}</span>
                            <button type="button" onclick="removeSection(this)" class="text-xs text-destructive hover:underline">Remove</button>
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
    row.dataset.page = page;
    row.innerHTML = '<div class="mb-3 flex items-center justify-between">'
        + '<span class="text-xs font-medium text-muted-foreground">Section ' + count + '</span>'
        + '<button type="button" onclick="removeSection(this)" class="text-xs text-destructive hover:underline">Remove</button>'
        + '</div>'
        + '<div class="grid gap-3 sm:grid-cols-[1fr_2fr]">'
        + '<div><label class="admin-label">Heading</label><input class="input-shell w-full section-title" value="" placeholder="Section heading"></div>'
        + '<div><label class="admin-label">Body</label><textarea class="input-shell w-full section-body" rows="3" placeholder="Section body text"></textarea></div>'
        + '</div>';
    container.appendChild(row);
    renumberSections(container);
}

function removeSection(btn) {
    var row = btn.closest('.legal-section-row');
    var container = row.parentElement;
    row.remove();
    renumberSections(container);
}

function renumberSections(container) {
    container.querySelectorAll('.legal-section-row').forEach(function(row, i) {
        var label = row.querySelector('span.text-xs');
        if (label) label.textContent = 'Section ' + (i + 1);
    });
}

function serializeSections(page) {
    var rows = document.querySelectorAll('#' + page + '-sections .legal-section-row');
    var sections = [];
    rows.forEach(function(row) {
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
