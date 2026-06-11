<x-layouts.admin :company="$company" page-heading="Payments">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-display text-3xl">Payments</h1>
                <p class="mt-2 text-sm text-muted-foreground">Track receipts, due amounts, and payment records across every assigned project.</p>
            </div>
            <div class="rounded-sm border border-border bg-card px-4 py-3 text-xs uppercase tracking-[0.18em] text-muted-foreground">
                {{ $payments->count() }} payment records
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Total Received</p>
                <p class="mt-2 font-display text-3xl">NGN {{ number_format((float) $payments->where('status', 'paid')->sum('amount')) }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">This Month</p>
                <p class="mt-2 font-display text-3xl">NGN {{ number_format((float) $payments->filter(fn ($payment) => optional($payment->paid_at)->isSameMonth(now()))->sum('amount')) }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Pending</p>
                <p class="mt-2 font-display text-3xl">NGN {{ number_format((float) $payments->where('status', 'pending')->sum('amount')) }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Outstanding</p>
                <p class="mt-2 font-display text-3xl">NGN {{ number_format((float) $assignments->sum('outstanding_balance')) }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="luxury-card overflow-hidden p-0">
                <div class="overflow-x-auto">
                    <table class="table-shell">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Client</th>
                                <th>Project</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr class="table-row-shell">
                                    <td class="font-mono text-xs text-muted-foreground">{{ $payment->reference ?: 'Auto-generated' }}</td>
                                    <td>{{ $payment->assignment?->user?->name ?: 'Unassigned client' }}</td>
                                    <td>{{ $payment->assignment?->project?->title ?: 'No project linked' }}</td>
                                    <td>NGN {{ number_format((float) $payment->amount) }}</td>
                                    <td>{{ optional($payment->paid_at ?: $payment->due_at)->format('M j, Y') ?: 'Pending date' }}</td>
                                    <td>{{ $payment->payment_method ?: 'Not set' }}</td>
                                    <td><x-status-badge :value="$payment->status" /></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-sm text-muted-foreground">No payments have been recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="luxury-card">
                <h2 class="font-display text-3xl">Record Payment</h2>
                <p class="mt-2 text-sm text-muted-foreground">Log a client payment and update the assignment balance instantly.</p>

                <form action="{{ route('admin.payments.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-xs uppercase tracking-[0.18em] text-muted-foreground">Assignment</label>
                        <select name="project_assignment_id" class="input-shell">
                            @foreach ($assignments as $assignment)
                                <option value="{{ $assignment->id }}">{{ $assignment->user?->name ?? 'Client' }} &middot; {{ $assignment->project?->title ?? 'Project' }} &middot; {{ $assignment->unit?->label ?? $assignment->unit_label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Amount (NGN)</label>
                        <input type="number" name="amount" required placeholder="0.00" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Status</label>
                        <select name="status" class="input-shell">
                            <option value="paid">Paid</option>
                            <option value="due">Due</option>
                            <option value="pending">Pending</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Reference Code</label>
                        <input name="reference" placeholder="e.g. TRF-001" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Payment Method</label>
                        <input name="payment_method" placeholder="e.g. Bank Transfer" class="input-shell">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="admin-label">Due Date</label>
                            <input type="date" name="due_at" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Paid Date</label>
                            <input type="date" name="paid_at" class="input-shell">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Notes</label>
                        <textarea name="notes" rows="3" class="input-shell"></textarea>
                    </div>
                    <button class="luxury-btn-primary w-full">Save Payment</button>
                </form>
            </div>
        </div>

        <div class="luxury-card overflow-hidden p-0">
            <div class="border-b border-border p-5">
                <h2 class="font-display text-2xl">Client Financials</h2>
                <p class="mt-2 text-sm text-muted-foreground">Update contract totals, paid amounts, outstanding balances, and next payment dates only.</p>
            </div>

            <div class="divide-y divide-border/60">
                @forelse ($assignments as $assignment)
                    <form action="{{ route('admin.payments.assignments.update', $assignment) }}" method="POST" class="grid gap-4 p-5 xl:grid-cols-[1.2fr_repeat(4,minmax(0,1fr))_minmax(140px,1fr)_120px] xl:items-end">
                        @csrf
                        @method('PUT')
                        <div>
                            <p class="font-medium">{{ $assignment->user?->name ?? 'Client' }}</p>
                            <p class="mt-1 text-xs text-muted-foreground">{{ $assignment->project?->title ?? 'Project' }} &middot; {{ $assignment->unit?->label ?? $assignment->unit_label }}</p>
                        </div>
                        <div>
                            <label class="admin-label">Contract</label>
                            <input type="number" step="0.01" name="contract_amount" value="{{ old('contract_amount', $assignment->contract_amount) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Paid</label>
                            <input type="number" step="0.01" name="amount_paid" value="{{ old('amount_paid', $assignment->amount_paid) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Outstanding</label>
                            <input type="number" step="0.01" name="outstanding_balance" value="{{ old('outstanding_balance', $assignment->outstanding_balance) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Next Amount</label>
                            <input type="number" step="0.01" name="next_payment_amount" value="{{ old('next_payment_amount', $assignment->next_payment_amount) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Next Due</label>
                            <input type="date" name="next_payment_due_at" value="{{ old('next_payment_due_at', optional($assignment->next_payment_due_at)->format('Y-m-d')) }}" class="input-shell">
                            <input type="hidden" name="status" value="{{ $assignment->status }}">
                        </div>
                        <button class="luxury-btn-outline">Update</button>
                    </form>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-muted-foreground">No client assignments are available for financial updates.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
