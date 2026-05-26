<form action="{{ route('portal.kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf

    <div>
        <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Full Name</label>
        <input name="full_name" value="{{ old('full_name') }}" required placeholder="John Doe" class="input-shell">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Phone Number</label>
        <input name="phone" value="{{ old('phone') }}" required placeholder="+234..." class="input-shell">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="john@example.com" class="input-shell">
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Address</label>
        <textarea name="residential_address" rows="3" required placeholder="Your residential address" class="input-shell">{{ old('residential_address') }}</textarea>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">ID Document</label>
        <div class="rounded-sm border-2 border-dashed border-border p-6 text-center">
            <p class="text-sm text-muted-foreground">Click to upload identification document</p>
            <input type="file" name="identification" data-preview-target="#kyc-file-preview" class="input-shell mt-4">
            <p id="kyc-file-preview" class="mt-2 text-xs text-muted-foreground">No file selected</p>
        </div>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Project of Interest</label>
        <select name="project_id" class="input-shell" @disabled(($projectOptions ?? collect())->isEmpty())>
            <option value="">
                {{ ($projectOptions ?? collect())->isEmpty() ? 'No published projects available yet' : 'Select a project' }}
            </option>
            @foreach (($projectOptions ?? collect()) as $projectOption)
                <option value="{{ $projectOption->id }}" @selected((string) old('project_id') === (string) $projectOption->id)>
                    {{ $projectOption->title }}{{ $projectOption->location ? ' — '.$projectOption->location : '' }}
                </option>
            @endforeach
        </select>
        <p class="mt-2 text-xs text-muted-foreground">
            Projects are pulled from the Admin/CMS project list.
        </p>
    </div>

    <div>
        <label class="mb-1 block text-xs uppercase tracking-wider text-muted-foreground">Additional Note</label>
        <textarea name="note" rows="4" placeholder="Additional note" class="input-shell">{{ old('note') }}</textarea>
    </div>

    <button class="luxury-btn-primary mt-2 w-full justify-center">
        Submit Request
        <x-portal-icon name="arrow-right" class="h-4 w-4" />
    </button>
</form>
