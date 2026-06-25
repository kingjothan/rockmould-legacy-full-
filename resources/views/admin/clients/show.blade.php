<x-layouts.admin :company="$company" :page-heading="$client->name">
    <div class="space-y-6">
        {{-- Back link --}}
        <a href="{{ route('admin.clients.index') }}" class="flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-foreground">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Client Management
        </a>

        {{-- Client header --}}
        <div class="flex items-center gap-4">
            <div class="avatar-lg">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
            <div>
                <h1 class="font-display text-2xl font-semibold">{{ $client->name }}</h1>
                <p class="text-sm text-muted-foreground">{{ $client->email ?: $client->phone }}</p>
            </div>
            <div class="ml-auto">
                <x-status-badge :value="$client->status" />
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="grid gap-4 md:grid-cols-3">
            <div class="stat-card">
                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-sky-500/10">
                    <svg class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                </div>
                <p class="font-display text-3xl font-bold">{{ $client->assignments->count() }}</p>
                <p class="mt-1 text-xs text-muted-foreground">Assigned Projects</p>
            </div>
            <div class="stat-card">
                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500/10">
                    <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg>
                </div>
                <p class="font-display text-2xl font-bold">NGN {{ number_format((float) $client->assignments->sum('amount_paid')) }}</p>
                <p class="mt-1 text-xs text-muted-foreground">Amount Paid</p>
            </div>
            <div class="stat-card">
                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/10">
                    <svg class="h-5 w-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="font-display text-2xl font-bold">NGN {{ number_format((float) $client->assignments->sum('outstanding_balance')) }}</p>
                <p class="mt-1 text-xs text-muted-foreground">Outstanding</p>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="space-y-6">
                <div class="luxury-card-flat overflow-hidden p-0">
                    <div class="border-b border-border px-6 py-4">
                        <h2 class="font-display text-lg font-semibold">Client Details</h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.clients.update', $client) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="admin-label">Full Name</label>
                                <input name="name" value="{{ old('name', $client->name) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $client->email) }}" required class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Phone Number</label>
                                <input name="phone" value="{{ old('phone', $client->phone) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Address</label>
                                <textarea name="address" rows="3" class="input-shell">{{ old('address', $client->address) }}</textarea>
                            </div>
                            <div>
                                <label class="admin-label">Client Notes / Bio</label>
                                <textarea name="bio" rows="4" class="input-shell">{{ old('bio', $client->bio) }}</textarea>
                            </div>
                            <div>
                                <label class="admin-label">Status</label>
                                <select name="status" class="input-shell">
                                    <option value="active" @selected($client->status === 'active')>Active</option>
                                    <option value="inactive" @selected($client->status === 'inactive')>Inactive</option>
                                </select>
                            </div>
                            <button class="luxury-btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>

                <div class="luxury-card-flat overflow-hidden p-0">
                    <div class="flex items-center justify-between border-b border-border px-6 py-4">
                        <h2 class="font-display text-lg font-semibold">Support Tickets</h2>
                        <span class="text-xs text-muted-foreground">{{ $client->supportTickets->count() }} tickets</span>
                    </div>
                    <div class="divide-y divide-border/60">
                        @forelse ($client->supportTickets as $ticket)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-sm font-medium">{{ $ticket->subject }}</p>
                                    <x-status-badge :value="$ticket->status" />
                                </div>
                                <p class="mt-1 text-xs text-muted-foreground">{{ optional($ticket->created_at)->format('M j, Y') }}</p>
                                <p class="mt-2 text-sm text-muted-foreground">{{ $ticket->message }}</p>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-sm text-muted-foreground">No support tickets for this client yet.</div>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card-flat overflow-hidden p-0">
                    <div class="flex items-center justify-between border-b border-border px-6 py-4">
                        <h2 class="font-display text-lg font-semibold">OTP Logs</h2>
                        <span class="text-xs text-muted-foreground">{{ $client->otps->count() }} recent</span>
                    </div>
                    <div class="divide-y divide-border/60">
                        @forelse ($client->otps as $otp)
                            <div class="px-6 py-4 text-sm">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-medium">{{ $otp->phone }}</p>
                                    <x-status-badge :value="$otp->delivery_status" />
                                </div>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    Sent {{ optional($otp->sent_at)->diffForHumans() ?? 'not sent' }}
                                    &middot; expires {{ optional($otp->expires_at)->format('M j, Y g:i A') }}
                                    &middot; attempts {{ $otp->attempts }}
                                </p>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-sm text-muted-foreground">No OTP activity for this client yet.</div>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card-flat border-destructive/30 p-6">
                    <h2 class="font-display text-lg font-semibold text-destructive">Remove Client</h2>
                    <p class="mt-2 text-sm text-muted-foreground">
                        This clears the client account and removes their project assignments. Assigned units will be released back to available.
                    </p>

                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="mt-5 space-y-4" onsubmit="return confirm('Delete this client permanently?');">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label class="admin-label">Type client email to confirm</label>
                            <input type="email" name="confirm_email" placeholder="{{ $client->email }}" class="input-shell">
                            @error('confirm_email')
                                <p class="mt-2 text-xs text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="rounded-sm border border-destructive/50 px-4 py-2 text-sm font-semibold text-destructive transition hover:bg-destructive hover:text-destructive-foreground">
                            Delete Client
                        </button>
                    </form>
                </div>
            </div>

            <div class="luxury-card-flat overflow-hidden p-0">
                <div class="flex items-center justify-between border-b border-border px-6 py-4">
                    <h2 class="font-display text-lg font-semibold">Assigned Projects</h2>
                    <span class="text-xs text-muted-foreground">{{ $client->assignments->count() }} assignments</span>
                </div>
                <div class="divide-y divide-border/60">
                    @forelse ($client->assignments as $assignment)
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-medium">{{ $assignment->project?->title ?? 'Unassigned project' }}</p>
                                    <p class="mt-0.5 text-sm text-muted-foreground">{{ $assignment->unit?->label ?? $assignment->unit_label }}</p>
                                </div>
                                <x-status-badge :value="$assignment->status" />
                            </div>
                            <div class="mt-3">
                                <div class="mb-1.5 flex justify-between text-xs text-muted-foreground">
                                    <span>Progress</span>
                                    <span>{{ $assignment->progress_percentage }}%</span>
                                </div>
                                <div class="h-1.5 overflow-hidden rounded-full bg-muted">
                                    <div class="h-full rounded-full bg-primary" style="width: {{ $assignment->progress_percentage }}%"></div>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-md bg-muted/30 px-3 py-2">
                                    <p class="text-xs text-muted-foreground">Paid</p>
                                    <p class="mt-1 font-medium text-emerald-400">NGN {{ number_format((float) $assignment->amount_paid) }}</p>
                                </div>
                                <div class="rounded-md bg-muted/30 px-3 py-2">
                                    <p class="text-xs text-muted-foreground">Outstanding</p>
                                    <p class="mt-1 font-medium text-primary">NGN {{ number_format((float) $assignment->outstanding_balance) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <p class="text-sm text-muted-foreground">No projects assigned to this client yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
