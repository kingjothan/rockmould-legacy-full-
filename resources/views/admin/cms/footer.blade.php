<x-layouts.admin :company="$company" page-heading="Footer CMS">
    <form action="{{ route('admin.cms.footer.update') }}" method="POST" class="space-y-8" data-footer-form>
        @csrf
        @method('PUT')

        <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="space-y-6">
                <div class="luxury-card space-y-4">
                    <h2 class="text-base font-semibold">Footer Text</h2>
                    <div>
                        <label class="admin-label">Company Tagline</label>
                        <textarea name="tagline" rows="3" class="input-shell w-full" placeholder="Short description shown below the logo in the footer">{{ $footer['tagline'] ?? 'Building legacies in real estate and construction. Premium developments, enduring quality, and unwavering commitment to excellence.' }}</textarea>
                    </div>
                    <div>
                        <label class="admin-label">Newsletter Title</label>
                        <input name="newsletter_title" value="{{ $footer['newsletter_title'] ?? 'Stay Informed' }}" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Newsletter Description</label>
                        <input name="newsletter_description" value="{{ $footer['newsletter_description'] ?? 'Subscribe for project updates and market insights.' }}" class="input-shell w-full">
                    </div>
                </div>

                <div class="luxury-card space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold">Footer Link Groups</h2>
                        <button type="button" data-add-group class="luxury-btn-outline text-xs">+ Add Group</button>
                    </div>
                    <p class="text-xs text-muted-foreground">Each group becomes a column in the footer. Add links to each group.</p>
                    <div data-footer-groups data-initial='@json(\App\Support\SiteData::footerGroups())' class="space-y-4"></div>
                    <input type="hidden" name="groups_json" data-groups-output>
                </div>
            </div>

            <div class="luxury-card">
                <h2 class="text-base font-semibold">Preview</h2>
                <p class="mt-2 text-xs text-muted-foreground">The footer also shows company phone, email, and address from Settings. The newsletter subscribe form is automatic.</p>
                <div class="mt-4 rounded-lg border border-border bg-background p-4">
                    <p class="text-xs text-muted-foreground">Current footer groups:</p>
                    @foreach (\App\Support\SiteData::footerGroups() as $group)
                        <div class="mt-3">
                            <p class="text-xs font-bold uppercase tracking-wider text-primary">{{ $group['title'] }}</p>
                            <ul class="mt-1 space-y-0.5">
                                @foreach ($group['links'] as $link)
                                    <li class="text-xs text-muted-foreground">{{ $link['label'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button class="luxury-btn-primary">Save Footer</button>
    </form>
</x-layouts.admin>
