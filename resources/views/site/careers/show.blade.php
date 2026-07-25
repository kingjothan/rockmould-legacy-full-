<x-layouts.public :company="$company" :title="$role->title.' | Careers | Rockmould Limited'">
    <x-hero :title="$role->title" subtitle="Careers" :description="$role->summary" :image="$role->hero_image ?: 'images/project-3.jpg'" />

    <section class="section-padding">
        <div class="container-luxury">
            {{-- Back link --}}
            <a href="{{ route('careers') }}" class="mb-8 flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-foreground">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Careers
            </a>

            <div class="grid gap-12 lg:grid-cols-[1.4fr_0.9fr]">
                <div class="space-y-8">
                    <div class="luxury-card">
                        <div class="mb-4 flex flex-wrap items-center gap-3 text-xs text-muted-foreground">
                            <span class="highlight-chip">{{ $role->department }}</span>
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                {{ $role->location }}
                            </span>
                            <span>{{ $role->employment_type }}</span>
                        </div>
                        <h2 class="font-display text-2xl font-semibold">Role Overview</h2>
                        <p class="mt-4 leading-relaxed text-muted-foreground">{{ $role->description ?: $role->summary }}</p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="luxury-card">
                            <h3 class="mb-5 font-display text-xl font-semibold">Requirements</h3>
                            <div class="space-y-3">
                                @foreach ($role->requirements ?? [] as $requirement)
                                    <div class="check-item">
                                        <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-sm text-muted-foreground">{{ $requirement }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="luxury-card">
                            <h3 class="mb-5 font-display text-xl font-semibold">Benefits</h3>
                            <div class="space-y-3">
                                @foreach ($role->benefits ?? [] as $benefit)
                                    <div class="check-item">
                                        <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                        <span class="text-sm text-muted-foreground">{{ $benefit }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="luxury-card">
                        <h3 class="mb-5 font-display text-xl font-semibold">Express Interest</h3>
                        @if (session('success'))
                            <div class="mb-4 flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ session('success') }}
                            </div>
                        @endif
                        <form action="{{ route('forms.career', $role->slug) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="admin-label">Full Name</label>
                                <input name="full_name" required class="input-shell" value="{{ old('full_name') }}">
                            </div>
                            <div>
                                <label class="admin-label">Email Address</label>
                                <input name="email" type="email" required class="input-shell" value="{{ old('email') }}">
                            </div>
                            <div>
                                <label class="admin-label">Phone Number</label>
                                <input name="phone" class="input-shell" value="{{ old('phone') }}">
                            </div>
                            <div>
                                <label class="admin-label">Why you're a fit</label>
                                <textarea name="message" rows="5" required class="input-shell">{{ old('message') }}</textarea>
                            </div>
                            <button class="luxury-btn-primary w-full">Submit Application</button>
                        </form>
                    </div>

                    @if ($otherRoles->isNotEmpty())
                        <div class="luxury-card">
                            <h3 class="mb-4 font-display text-lg font-semibold">Other Openings</h3>
                            <div class="space-y-2">
                                @foreach ($otherRoles as $otherRole)
                                    <a href="{{ route('careers.show', $otherRole->slug) }}"
                                       class="flex items-center justify-between rounded-md border border-border px-4 py-3 text-sm text-muted-foreground transition-colors hover:border-primary/30 hover:text-foreground">
                                        {{ $otherRole->title }}
                                        <svg class="h-3.5 w-3.5 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
