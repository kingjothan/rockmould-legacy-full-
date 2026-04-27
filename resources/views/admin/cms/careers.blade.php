<x-layouts.admin :company="$company" page-heading="Careers CMS">
    <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-5">
            @foreach ($roles as $role)
                <div class="luxury-card">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 class="font-display text-3xl">{{ $role->title }}</h2>
                            <p class="mt-2 text-xs uppercase tracking-[0.18em] text-muted-foreground">
                                {{ $role->department }} &middot; {{ $role->location }} &middot; {{ $role->employment_type }}
                            </p>
                        </div>
                        <x-status-badge :value="$role->is_open ? 'open' : 'closed'" />
                    </div>
                    <form action="{{ route('admin.cms.careers.update', $role) }}" method="POST" class="mt-5 space-y-4">
                        @csrf @method('PUT')
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Role Title</label>
                                <input name="title" value="{{ $role->title }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Slug</label>
                                <input name="slug" value="{{ $role->slug }}" class="input-shell" placeholder="Auto-generated if left blank">
                            </div>
                            <div>
                                <label class="admin-label">Department</label>
                                <input name="department" value="{{ $role->department }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Location</label>
                                <input name="location" value="{{ $role->location }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Employment Type</label>
                                <input name="employment_type" value="{{ $role->employment_type }}" class="input-shell">
                            </div>
                            <label class="inline-flex items-center gap-3 rounded-lg border border-border bg-background/50 px-4 py-3 text-sm text-foreground">
                                <input type="checkbox" name="is_open" value="1" @checked($role->is_open) class="h-4 w-4 rounded border-border bg-background text-primary focus:ring-primary">
                                <span>Role is open for applications</span>
                            </label>
                        </div>
                        <div>
                            <label class="admin-label">Summary</label>
                            <textarea name="summary" rows="3" class="input-shell">{{ $role->summary }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Description</label>
                            <textarea name="description" rows="4" class="input-shell">{{ $role->description }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Requirements</label>
                                <textarea name="requirements_text" rows="5" class="input-shell">{{ implode("\n", $role->requirements ?? []) }}</textarea>
                            </div>
                            <div>
                                <label class="admin-label">Benefits</label>
                                <textarea name="benefits_text" rows="5" class="input-shell">{{ implode("\n", $role->benefits ?? []) }}</textarea>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button class="luxury-btn-primary">Update Role</button>
                            <button formaction="{{ route('admin.cms.careers.destroy', $role) }}" formmethod="POST" name="_method" value="DELETE" onclick="return confirm('Delete this career role? This action cannot be undone.')" class="luxury-btn-outline border-red-500/30 text-red-300 hover:border-red-500/50 hover:text-red-200">
                                Delete Role
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="luxury-card">
            <h2 class="font-display text-3xl">Add Career Role</h2>
            <form action="{{ route('admin.cms.careers.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="admin-label">Role Title</label>
                    <input name="title" required placeholder="Role title" class="input-shell">
                </div>
                <div>
                    <label class="admin-label">Slug</label>
                    <input name="slug" placeholder="Optional custom slug" class="input-shell">
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="admin-label">Department</label>
                        <input name="department" required placeholder="Department" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Location</label>
                        <input name="location" required placeholder="Location" class="input-shell">
                    </div>
                </div>
                <div>
                    <label class="admin-label">Employment Type</label>
                    <input name="employment_type" required placeholder="Employment type" class="input-shell">
                </div>
                <div>
                    <label class="admin-label">Summary</label>
                    <textarea name="summary" rows="3" required placeholder="Summary" class="input-shell"></textarea>
                </div>
                <div>
                    <label class="admin-label">Description</label>
                    <textarea name="description" rows="4" placeholder="Description" class="input-shell"></textarea>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="admin-label">Requirements</label>
                        <textarea name="requirements_text" rows="4" placeholder="Requirements, one per line" class="input-shell"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Benefits</label>
                        <textarea name="benefits_text" rows="4" placeholder="Benefits, one per line" class="input-shell"></textarea>
                    </div>
                </div>
                <label class="inline-flex items-center gap-3 rounded-lg border border-border bg-background/50 px-4 py-3 text-sm text-foreground">
                    <input type="checkbox" name="is_open" value="1" checked class="h-4 w-4 rounded border-border bg-background text-primary focus:ring-primary">
                    <span>Publish this role as open immediately</span>
                </label>
                <button class="luxury-btn-primary w-full">Create Career Role</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
