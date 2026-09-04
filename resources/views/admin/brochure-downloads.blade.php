<x-layouts.admin :company="$company" page-heading="Brochure Downloads">
    <div class="mx-auto max-w-7xl space-y-6">

        <div>
            <h1 class="mb-1 font-display text-3xl font-semibold">Brochure Downloads</h1>
            <p class="text-sm text-muted-foreground">People who filled the form to download a project brochure from the public site.</p>
        </div>

        <div class="luxury-card overflow-hidden p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border bg-muted/30 text-left text-xs uppercase tracking-widest text-muted-foreground">
                            <th class="px-5 py-3">Name</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Phone</th>
                            <th class="px-5 py-3">Project</th>
                            <th class="px-5 py-3">IP Address</th>
                            <th class="px-5 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($downloads as $download)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-5 py-3 font-medium">{{ $download->name }}</td>
                                <td class="px-5 py-3 text-muted-foreground">
                                    <a href="mailto:{{ $download->email }}" class="hover:text-primary transition-colors">{{ $download->email }}</a>
                                </td>
                                <td class="px-5 py-3 text-muted-foreground">{{ $download->phone }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs font-medium text-primary">{{ $download->project?->title ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3 text-xs text-muted-foreground">{{ $download->ip_address ?? '—' }}</td>
                                <td class="px-5 py-3 text-xs text-muted-foreground">{{ $download->created_at->format('M j, Y g:ia') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-muted-foreground">No brochure downloads yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($downloads->hasPages())
                <div class="border-t border-border px-5 py-4">
                    {{ $downloads->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.admin>
