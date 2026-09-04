<x-layouts.admin :company="$company" page-heading="Client Management">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-display text-3xl">Client Management</h1>
                <p class="mt-2 text-sm text-muted-foreground">Manage client identities, portfolio counts, and account status across the Rockmould system.</p>
            </div>
            <div class="rounded-sm border border-border bg-card px-4 py-3 text-xs uppercase tracking-[0.18em] text-muted-foreground">
                {{ $clients->count() }} client records
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Total Clients</p>
                <p class="mt-2 font-display text-3xl">{{ $clients->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Active</p>
                <p class="mt-2 font-display text-3xl">{{ $clients->where('status', 'active')->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Total Paid</p>
                <p class="mt-2 font-display text-3xl">NGN {{ number_format((float) $clients->sum(fn ($client) => $client->assignments->sum('amount_paid'))) }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Outstanding</p>
                <p class="mt-2 font-display text-3xl">NGN {{ number_format((float) $clients->sum(fn ($client) => $client->assignments->sum('outstanding_balance'))) }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1fr_360px]">
            <div class="luxury-card min-w-0 p-0">
                <div class="border-b border-border p-5">
                    <form method="GET" class="flex flex-wrap gap-3">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search clients..." class="input-shell min-w-[180px] flex-1">
                        <select name="status" class="input-shell w-auto min-w-[150px]">
                            <option value="">All statuses</option>
                            @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button class="luxury-btn-outline shrink-0">Filter</button>
                    </form>
                </div>

                <div>
                    <table class="table-shell w-full">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="text-center">Projects</th>
                                <th>Paid / Outstanding</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clients as $client)
                                <tr class="table-row-shell">
                                    <td>
                                        <div class="font-medium leading-snug">{{ $client->name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $client->email ?: 'No email on file' }}</div>
                                    </td>
                                    <td class="text-sm">{{ $client->phone }}</td>
                                    <td><x-status-badge :value="$client->status" /></td>
                                    <td class="text-center text-sm">{{ $client->assignments->count() }}</td>
                                    <td class="text-sm">
                                        <div>{{ number_format((float) $client->assignments->sum('amount_paid') / 1000) }}K</div>
                                        <div class="text-xs text-primary">{{ number_format((float) $client->assignments->sum('outstanding_balance') / 1000) }}K out</div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.clients.show', $client) }}" class="text-primary transition hover:text-primary/80">Open</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-sm text-muted-foreground">No client records matched the current filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="luxury-card">
                <h2 class="font-display text-3xl">Add Client</h2>
                <p class="mt-2 text-sm text-muted-foreground">Create a new client account for portal access and project assignment.</p>

                <form action="{{ route('admin.clients.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-xs uppercase tracking-[0.18em] text-muted-foreground">Client Name</label>
                        <input name="name" required placeholder="Client name" class="input-shell">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs uppercase tracking-[0.18em] text-muted-foreground">Email Address</label>
                        <input type="email" name="email" required placeholder="Email address" class="input-shell">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs uppercase tracking-[0.18em] text-muted-foreground">Phone Number</label>
                        <input name="phone" required placeholder="Phone number" class="input-shell">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs uppercase tracking-[0.18em] text-muted-foreground">Address</label>
                        <textarea name="address" rows="3" placeholder="Address" class="input-shell"></textarea>
                    </div>
                    <div>
                        <label class="mb-2 block text-xs uppercase tracking-[0.18em] text-muted-foreground">Status</label>
                        <select name="status" class="input-shell">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button class="luxury-btn-primary w-full">Create Client</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
