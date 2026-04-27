<x-layouts.admin :company="$company" page-heading="Timeline and Updates">
    <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="space-y-5">
            @foreach ($updates as $update)
                <div class="luxury-card">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-primary">{{ $update->project?->title }}</p>
                            <h2 class="mt-2 font-display text-3xl">{{ $update->title }}</h2>
                        </div>
                        <x-status-badge :value="$update->visible_to_client ? 'published' : 'internal'" />
                    </div>
                    <p class="mt-4 text-muted-foreground">{{ $update->body }}</p>
                </div>
            @endforeach
        </div>
        <div class="luxury-card">
            <h2 class="font-display text-3xl">Add Update</h2>
            <form action="{{ route('admin.updates.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <select name="project_id" class="input-shell">@foreach ($projects as $project)<option value="{{ $project->id }}">{{ $project->title }}</option>@endforeach</select>
                <input name="title" required placeholder="Update title" class="input-shell">
                <textarea name="body" rows="5" required placeholder="Update details" class="input-shell"></textarea>
                <input type="date" name="update_date" value="{{ now()->toDateString() }}" class="input-shell">
                <input type="number" name="progress" min="0" max="100" placeholder="Progress %" class="input-shell">
                <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="visible_to_client" value="1" checked> Visible to client</label>
                <button class="luxury-btn-primary w-full">Publish Update</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
