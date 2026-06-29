<x-layouts.portal :company="$company" :page-heading="$content['title'] ?? 'Balance Sheet'">
    <div class="space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.24em] text-primary/70">{{ $content['kicker'] ?? 'Financial Overview' }}</p>
            <h1 class="mt-2 font-display text-4xl md:text-5xl">{{ $content['title'] ?? 'A clear view of payments, balances, and next obligations.' }}</h1>
            <p class="mt-3 max-w-2xl text-sm text-muted-foreground md:text-base">
                {{ $content['description'] ?? 'Review project value, total paid, current outstanding balance, and each recorded installment tied to your Rockmould account.' }}
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['contract_label'] ?? 'Total Project Value' }}</p>
                <p class="mt-2 font-display text-4xl">NGN {{ number_format((float) $totals['contract']) }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['paid_label'] ?? 'Total Paid' }}</p>
                <p class="mt-2 font-display text-4xl text-primary">NGN {{ number_format((float) $totals['paid']) }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['outstanding_label'] ?? 'Outstanding Balance' }}</p>
                <p class="mt-2 font-display text-4xl">NGN {{ number_format((float) $totals['outstanding']) }}</p>
            </div>
        </div>

        <div class="space-y-6">
            @forelse ($assignments as $assignment)
                <section class="luxury-card">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-primary/70">{{ $assignment->project?->title ?? 'Project' }}</p>
                            <h2 class="mt-2 font-display text-3xl">{{ $assignment->unit?->label ?? $assignment->unit_label }}</h2>
                            <p class="mt-2 text-sm text-muted-foreground">{{ $assignment->project?->location ?? 'Location pending' }} &middot; {{ str($assignment->status)->title() }}</p>
                        </div>
                        <x-status-badge :value="$assignment->status" />
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-sm border border-border bg-muted/20 p-4">
                            <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Contract Amount</p>
                            <p class="mt-2 font-display text-2xl">NGN {{ number_format((float) $assignment->contract_amount) }}</p>
                        </div>
                        <div class="rounded-sm border border-border bg-muted/20 p-4">
                            <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Amount Paid</p>
                            <p class="mt-2 font-display text-2xl text-primary">NGN {{ number_format((float) $assignment->amount_paid) }}</p>
                        </div>
                        <div class="rounded-sm border border-border bg-muted/20 p-4">
                            <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Outstanding</p>
                            <p class="mt-2 font-display text-2xl">NGN {{ number_format((float) $assignment->outstanding_balance) }}</p>
                        </div>
                    </div>

                    @if ($assignment->payments->isNotEmpty())
                        <div class="mt-8">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <h3 class="font-display text-2xl">{{ $content['history_title'] ?? 'Payment History' }}</h3>
                                <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $assignment->payments->count() }} recorded payments</p>
                            </div>
                            <div class="space-y-1">
                                @foreach ($assignment->payments as $index => $payment)
                                    <div class="flex flex-col gap-4 border-border py-4 md:flex-row md:items-center md:justify-between {{ $index > 0 ? 'border-t' : '' }}">
                                        <div class="flex items-start gap-4">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg>
                            </div>
                                            <div>
                                                <p class="text-sm font-medium">{{ $payment->reference ?: 'Project payment record' }}</p>
                                                <p class="mt-1 text-xs text-muted-foreground">
                                                    {{ optional($payment->paid_at ?: $payment->due_at)->format('M j, Y') ?: 'Pending date' }}
                                                    &middot; {{ $assignment->project?->title ?? 'Project' }}
                                                    @if ($payment->payment_method)
                                                        &middot; {{ $payment->payment_method }}
                                                    @endif
                                                </p>
                                                @if ($payment->notes)
                                                    <p class="mt-2 text-sm text-muted-foreground">{{ $payment->notes }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-4 md:text-right">
                                            <div>
                                                <p class="font-medium text-primary">NGN {{ number_format((float) $payment->amount) }}</p>
                                                <p class="mt-1 text-xs text-muted-foreground">{{ str($payment->status)->title() }}</p>
                                            </div>
                                            <x-status-badge :value="$payment->status" />
                                            <a href="{{ route('portal.balance.receipt', $payment) }}" class="text-xs text-primary hover:underline" title="Download Receipt">
                                                <x-portal-icon name="file-text" class="h-4 w-4" />
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-8 rounded-sm border border-dashed border-border px-5 py-8 text-center">
                            <p class="text-sm text-muted-foreground">No payment records have been published for this project yet.</p>
                        </div>
                    @endif
                </section>
            @empty
                <div class="luxury-card py-16 text-center">
                    <h2 class="font-display text-3xl">{{ $content['empty_title'] ?? 'No financial records yet.' }}</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm text-muted-foreground">
                        {{ $content['empty_description'] ?? 'Your balance sheet will become available after the first project assignment is activated on your portal.' }}
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.portal>
