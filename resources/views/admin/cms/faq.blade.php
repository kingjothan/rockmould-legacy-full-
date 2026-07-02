<x-layouts.admin :company="$company" page-heading="FAQ Management">
    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">

        {{-- Left: existing FAQs grouped by category --}}
        <div class="space-y-3">
            @php $grouped = $faqs->groupBy('category'); @endphp
            @forelse ($grouped as $category => $items)
                <details class="luxury-card group" open>
                    <summary class="cursor-pointer list-none">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="h-5 w-0.5 rounded-full bg-primary"></span>
                                <h2 class="font-display text-lg font-semibold">{{ $category }}</h2>
                                <span class="rounded-full bg-primary/10 px-2 py-0.5 text-xs text-primary">{{ $items->count() }}</span>
                            </div>
                            <x-portal-icon name="chevron-down" class="h-4 w-4 text-muted-foreground transition-transform group-open:rotate-180" />
                        </div>
                    </summary>

                    <div class="mt-4 space-y-3">
                        @foreach ($items as $faq)
                            <details class="rounded-lg border border-border bg-muted/30 group/item">
                                <summary class="cursor-pointer list-none p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="text-sm font-medium leading-snug">{{ $faq->question }}</p>
                                        <x-portal-icon name="chevron-down" class="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground transition-transform group-open/item:rotate-180" />
                                    </div>
                                </summary>
                                <div class="px-4 pb-4">
                                    <p class="mb-4 text-xs leading-relaxed text-muted-foreground">{{ $faq->answer }}</p>
                                    <form action="{{ route('admin.cms.faq.update', $faq) }}" method="POST" class="space-y-3 border-t border-border pt-4">
                                        @csrf @method('PUT')
                                        <div>
                                            <label class="admin-label">Category</label>
                                            <input name="category" value="{{ $faq->category }}" required class="input-shell w-full">
                                        </div>
                                        <div>
                                            <label class="admin-label">Question</label>
                                            <input name="question" value="{{ $faq->question }}" required class="input-shell w-full">
                                        </div>
                                        <div>
                                            <label class="admin-label">Answer</label>
                                            <textarea name="answer" rows="4" required class="input-shell w-full">{{ $faq->answer }}</textarea>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <label class="flex items-center gap-2 text-sm text-muted-foreground">
                                                <input type="checkbox" name="is_published" value="1" @checked($faq->is_published) class="h-4 w-4 rounded border-border bg-transparent text-primary">
                                                Published
                                            </label>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button class="luxury-btn-primary text-sm">Save</button>
                                        </div>
                                    </form>
                                    <form action="{{ route('admin.cms.faq.destroy', $faq) }}" method="POST" class="mt-3"
                                        onsubmit="return confirm('Delete this FAQ? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-destructive hover:underline">Delete this FAQ</button>
                                    </form>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </details>
            @empty
                <div class="luxury-card text-center text-muted-foreground">No FAQs yet. Add one using the form.</div>
            @endforelse
        </div>

        {{-- Right: add new --}}
        <div>
            <div class="luxury-card sticky top-20">
                <h2 class="font-display text-xl font-semibold">Add FAQ</h2>
                <p class="mt-1 text-xs text-muted-foreground">Use one of the category names above to keep things grouped, or type a new one.</p>
                <form action="{{ route('admin.cms.faq.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="admin-label">Category</label>
                        <input name="category" required placeholder="e.g. Buying & Payment" class="input-shell w-full"
                            list="rm-category-list">
                        <datalist id="rm-category-list">
                            @foreach ($grouped ?? [] as $cat => $_)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="admin-label">Question</label>
                        <input name="question" required placeholder="What is your question?" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Answer</label>
                        <textarea name="answer" rows="5" required placeholder="Type the answer here…" class="input-shell w-full"></textarea>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-muted-foreground">
                        <input type="checkbox" name="is_published" value="1" checked class="h-4 w-4 rounded border-border bg-transparent text-primary">
                        Publish immediately
                    </label>
                    <button class="luxury-btn-primary w-full justify-center">Add FAQ</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
