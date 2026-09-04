<x-layouts.admin :company="$company" page-heading="Legal Pages CMS">
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-400">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.cms.legal.update') }}" method="POST" class="space-y-10">
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

            @foreach ([
                ['s1', 'Data Collection', 'Rockmould Limited collects contact details, project inquiry information, client portal records, uploaded documents, and support communications to deliver services and maintain operational records.'],
                ['s2', 'How We Use Your Data', 'Information provided through contact forms, partnerships, newsletters, KYC requests, or the client portal may be used for relationship management, project reporting, account review, and compliance workflows.'],
                ['s3', 'Data Protection', 'We do not sell personal information. Access to sensitive records is restricted to authorized Rockmould personnel who require it for legitimate operational, finance, project, or support purposes.'],
                ['s4', 'Contact & Corrections', 'If you need corrections, account review, or record clarification, contact us or use the portal support channel.'],
            ] as [$key, $defaultTitle, $defaultBody])
            <div class="grid gap-4 border-t border-border pt-4 sm:grid-cols-[1fr_2fr]">
                <div>
                    <label class="admin-label">Section Heading</label>
                    <input name="privacy_{{ $key }}_title" value="{{ $legal['privacy_' . $key . '_title'] ?? $defaultTitle }}" class="input-shell w-full">
                </div>
                <div>
                    <label class="admin-label">Section Body</label>
                    <textarea name="privacy_{{ $key }}_body" rows="3" class="input-shell w-full">{{ $legal['privacy_' . $key . '_body'] ?? $defaultBody }}</textarea>
                </div>
            </div>
            @endforeach
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

            @foreach ([
                ['s1', 'Website Use', 'Content on this website is provided for general information, project marketing, and relationship management purposes. Public site materials do not constitute a final commercial offer unless confirmed by Rockmould in writing.'],
                ['s2', 'Client Portal Access', 'Client portal access is intended only for recognized clients and approved users. Misuse of project data, documents, media, or support channels is prohibited.'],
                ['s3', 'Project Information', 'Project timelines, visuals, payment schedules, and availability may change. Official transaction documents, agreements, and internal approvals govern final commercial terms.'],
                ['s4', 'Acceptance of Terms', 'By using this website or portal, you agree to engage with Rockmould lawfully and in good faith. For questions about these terms, contact us.'],
            ] as [$key, $defaultTitle, $defaultBody])
            <div class="grid gap-4 border-t border-border pt-4 sm:grid-cols-[1fr_2fr]">
                <div>
                    <label class="admin-label">Section Heading</label>
                    <input name="terms_{{ $key }}_title" value="{{ $legal['terms_' . $key . '_title'] ?? $defaultTitle }}" class="input-shell w-full">
                </div>
                <div>
                    <label class="admin-label">Section Body</label>
                    <textarea name="terms_{{ $key }}_body" rows="3" class="input-shell w-full">{{ $legal['terms_' . $key . '_body'] ?? $defaultBody }}</textarea>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-end">
            <button type="submit" class="luxury-btn">Save Legal Pages</button>
        </div>
    </form>
</x-layouts.admin>
