<x-layouts.admin :company="$company" page-heading="Newsletter">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">Email Campaigns</p>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">Compose and send emails to your newsletter subscribers.</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                    <x-portal-icon name="bell" class="h-5 w-5 text-primary" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $subscribers->count() }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Active subscribers</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-sky-500/10">
                    <x-portal-icon name="mail" class="h-5 w-5 text-sky-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $subscribers->where('subscribed_at', '>=', now()->subDays(7))->count() }}</p>
                <p class="mt-1 text-sm text-muted-foreground">New this week</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/10">
                    <x-portal-icon name="check-circle" class="h-5 w-5 text-emerald-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $subscribers->count() > 0 ? 'Ready' : '—' }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Send status</p>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
            <div class="luxury-card-flat overflow-hidden p-0">
                <div class="border-b border-border px-6 py-4">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="mail" class="h-4 w-4 text-primary" />
                        <h2 class="text-base font-semibold">Compose Newsletter</h2>
                    </div>
                </div>
                <form action="{{ route('admin.newsletter.send') }}" method="POST" enctype="multipart/form-data" class="space-y-4 px-6 py-5" onsubmit="return confirm('Send this newsletter to {{ $subscribers->count() }} subscriber(s)?')">
                    @csrf
                    <div>
                        <label class="admin-label">Subject Line</label>
                        <input name="subject" value="{{ old('subject') }}" required placeholder="e.g. Rockmould Project Update — June 2026" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Email Body</label>
                        <p class="mb-2 text-xs text-muted-foreground">Write in plain text. Use blank lines to separate paragraphs.</p>
                        <textarea name="body" rows="10" required placeholder="Write your newsletter content here..." class="input-shell w-full">{{ old('body') }}</textarea>
                    </div>
                    <div>
                        <label class="admin-label">Images (optional)</label>
                        <p class="mb-2 text-xs text-muted-foreground">Upload multiple images or paste URLs (one per line).</p>
                        <textarea name="image_urls" rows="3" placeholder="Paste image URLs, one per line" class="input-shell w-full">{{ old('image_urls') }}</textarea>
                        <input type="file" name="image_uploads[]" accept="image/*" multiple class="input-shell mt-2 w-full">
                        <p class="mt-1 text-xs text-muted-foreground">Hold Ctrl/Cmd to select multiple files.</p>
                    </div>
                    <div>
                        <label class="admin-label">Videos (optional)</label>
                        <p class="mb-2 text-xs text-muted-foreground">Upload multiple videos or paste URLs (YouTube, Vimeo, or direct links — one per line).</p>
                        <textarea name="video_urls" rows="3" placeholder="Paste video URLs, one per line" class="input-shell w-full">{{ old('video_urls') }}</textarea>
                        <input type="file" name="video_uploads[]" accept="video/*" multiple class="input-shell mt-2 w-full">
                        <p class="mt-1 text-xs text-muted-foreground">Hold Ctrl/Cmd to select multiple files.</p>
                    </div>
                    <button class="luxury-btn-primary w-full justify-center">
                        Send to {{ $subscribers->count() }} Subscriber(s)
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                <div class="luxury-card">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-portal-icon name="bell" class="h-4 w-4 text-primary" />
                            <h2 class="text-base font-semibold">Subscribers</h2>
                        </div>
                        <span class="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">{{ $subscribers->count() }}</span>
                    </div>
                    <div class="mt-5 space-y-2">
                        @forelse ($subscribers as $sub)
                            <div class="flex items-center justify-between rounded-md border border-border bg-muted/20 px-4 py-3">
                                <div>
                                    <p class="text-sm">{{ $sub->email }}</p>
                                    <p class="text-xs text-muted-foreground">Joined {{ ($sub->subscribed_at ?? $sub->created_at)?->format('M j, Y') }}</p>
                                </div>
                                <form action="{{ route('admin.newsletter.unsubscribe', $sub) }}" method="POST" onsubmit="return confirm('Remove {{ $sub->email }}?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-xs text-destructive hover:underline">Remove</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No subscribers yet. Visitors can subscribe from the website footer or blog page.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
