<x-layouts.portal :company="$company" :page-heading="$content['title'] ?? 'Profile'">
    @php
        $initials = collect(explode(' ', (string) $user->name))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('');
        $supportAssignment = $user->assignments->first();
        $profileItems = [
            ['icon' => 'phone', 'label' => 'Phone', 'value' => $user->phone ?: 'No phone on file'],
            ['icon' => 'mail', 'label' => 'Email', 'value' => $user->email ?: 'No email added yet'],
            ['icon' => 'map-pin', 'label' => 'Address', 'value' => $user->address ?: 'No residential address on file'],
            ['icon' => 'file-text', 'label' => 'Linked Projects', 'value' => $user->assignments->pluck('project.title')->filter()->implode(', ') ?: 'No linked projects yet'],
            ['icon' => 'shield', 'label' => 'Verification Status', 'value' => $user->status === 'active' ? 'KYC Verified' : str($user->status)->title()],
        ];
    @endphp

    <div class="mx-auto max-w-3xl space-y-8">
        <h1 class="font-display text-2xl font-semibold">{{ $content['title'] ?? 'My Profile' }}</h1>

        <div class="luxury-card">
            <div class="mb-8 flex items-center gap-6">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-muted text-2xl font-display font-bold text-muted-foreground">{{ $initials ?: 'RM' }}</div>
                <div>
                    <h2 class="font-display text-xl font-semibold">{{ $user->name }}</h2>
                    <div class="mt-1 flex items-center gap-1">
                        <span class="status-completed">{{ $user->status === 'active' ? 'Verified' : str($user->status)->title() }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach ($profileItems as $item)
                    <div class="flex items-start gap-4 border-b border-border py-3 last:border-0">
                        <x-portal-icon :name="$item['icon']" class="mt-0.5 h-4 w-4 text-primary" />
                        <div>
                            <p class="text-xs uppercase tracking-wider text-muted-foreground">{{ $item['label'] }}</p>
                            <p class="mt-0.5 text-sm">{{ $item['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="luxury-card">
            <h3 class="mb-3 font-display text-lg font-semibold">{{ $content['support_title'] ?? 'Support Contact' }}</h3>
            @if ($supportAssignment)
                @php
                    $mgrName  = data_get($content, 'manager_name')  ?: ($supportAssignment->account_manager_name  ?: 'Rockmould Client Relations');
                    $mgrEmail = data_get($content, 'manager_email') ?: ($supportAssignment->account_manager_email ?: $company['email']);
                    $mgrPhone = data_get($content, 'manager_phone') ?: ($supportAssignment->account_manager_phone ?: $company['phone']);
                @endphp
                <p class="text-sm text-muted-foreground">Your Account Manager: <span class="font-medium text-foreground">{{ $mgrName }}</span></p>
                <p class="mt-1 text-sm text-muted-foreground">Email: <a href="mailto:{{ $mgrEmail }}" class="text-primary">{{ $mgrEmail }}</a></p>
                <p class="mt-1 text-sm text-muted-foreground">Phone: <a href="tel:{{ preg_replace('/\s+/', '', $mgrPhone) }}" class="text-primary">{{ $mgrPhone }}</a></p>
            @else
                <p class="text-sm text-muted-foreground">{{ $content['support_empty'] ?? 'A dedicated account manager will appear here once a project has been assigned to your portal.' }}</p>
            @endif
        </div>

        <div class="luxury-card">
            <h3 class="mb-3 font-display text-lg font-semibold">{{ $content['update_title'] ?? 'Update Profile' }}</h3>
            <form action="{{ route('portal.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email address" class="input-shell">
                <textarea name="address" rows="3" placeholder="Residential address" class="input-shell">{{ old('address', $user->address) }}</textarea>
                <textarea name="bio" rows="4" placeholder="Additional profile note" class="input-shell">{{ old('bio', $user->bio) }}</textarea>
                <button class="luxury-btn-primary">Save Profile</button>
            </form>
        </div>
    </div>
</x-layouts.portal>
