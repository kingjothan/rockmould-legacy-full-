@php
    $fmt = function (float $n): string {
        if ($n >= 1_000_000_000) return 'NGN ' . rtrim(rtrim(number_format($n / 1_000_000_000, 2), '0'), '.') . 'B';
        if ($n >= 1_000_000)     return 'NGN ' . rtrim(rtrim(number_format($n / 1_000_000, 2), '0'), '.') . 'M';
        if ($n >= 1_000)         return 'NGN ' . rtrim(rtrim(number_format($n / 1_000, 1), '0'), '.') . 'K';
        return 'NGN ' . number_format($n);
    };
    $fmtFull = fn (float $n): string => 'NGN ' . number_format($n);
@endphp
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
            @php $contract = (float) $totals['contract']; $paid = (float) $totals['paid']; $outstanding = (float) $totals['outstanding']; @endphp
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['contract_label'] ?? 'Total Project Value' }}</p>
                <p class="mt-2 font-display text-3xl leading-tight" title="{{ $fmtFull($contract) }}">{{ $fmt($contract) }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['paid_label'] ?? 'Total Paid' }}</p>
                <p class="mt-2 font-display text-3xl leading-tight text-primary" title="{{ $fmtFull($paid) }}">{{ $fmt($paid) }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['outstanding_label'] ?? 'Outstanding Balance' }}</p>
                <p class="mt-2 font-display text-3xl leading-tight" title="{{ $fmtFull($outstanding) }}">{{ $fmt($outstanding) }}</p>
            </div>
        </div>

        <div class="space-y-6">
            @forelse ($assignments as $assignment)
                <section class="luxury-card">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <a href="{{ route('portal.projects.show', $assignment) }}" class="group/proj inline-block">
                                <p class="text-xs uppercase tracking-[0.24em] text-primary/70 group-hover/proj:text-primary">{{ $assignment->project?->title ?? 'Project' }}</p>
                                <h2 class="mt-2 font-display text-3xl group-hover/proj:underline decoration-primary underline-offset-4">{{ $assignment->unit?->label ?? $assignment->unit_label }}</h2>
                            </a>
                            <p class="mt-2 text-sm text-muted-foreground">
                                {{ $assignment->project?->location ?? 'Location pending' }} &middot; {{ str($assignment->status)->title() }}
                                @if ($assignment->project?->slug)
                                    &middot; <a href="{{ route('projects.show', $assignment->project) }}" target="_blank" class="hover:text-primary hover:underline">View project &nearr;</a>
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <x-status-badge :value="$assignment->status" />
                            <a href="{{ route('portal.balance.statement', $assignment) }}"
                               class="inline-flex items-center gap-2 rounded-sm border border-primary/50 bg-primary/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.12em] text-primary transition hover:bg-primary/15"
                               title="Download Account Statement PDF">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Download Statement
                            </a>
                        </div>
                    </div>

                    @php
                        $aContract = (float) $assignment->contract_amount;
                        $aPaid     = (float) $assignment->amount_paid;
                        $aOutst    = (float) $assignment->outstanding_balance;
                    @endphp
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-sm border border-border bg-muted/20 p-4">
                            <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Contract Amount</p>
                            <p class="mt-2 font-display text-xl leading-tight" title="{{ $fmtFull($aContract) }}">{{ $fmt($aContract) }}</p>
                        </div>
                        <div class="rounded-sm border border-border bg-muted/20 p-4">
                            <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Amount Paid</p>
                            <p class="mt-2 font-display text-xl leading-tight text-primary" title="{{ $fmtFull($aPaid) }}">{{ $fmt($aPaid) }}</p>
                        </div>
                        <div class="rounded-sm border border-border bg-muted/20 p-4">
                            <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Outstanding</p>
                            <p class="mt-2 font-display text-xl leading-tight" title="{{ $fmtFull($aOutst) }}">{{ $fmt($aOutst) }}</p>
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

                                        <div class="flex flex-wrap items-center justify-end gap-3 md:text-right">
                                            <div class="mr-2">
                                                <p class="font-medium text-primary">NGN {{ number_format((float) $payment->amount) }}</p>
                                                <p class="mt-1 text-xs text-muted-foreground">{{ str($payment->status)->title() }}</p>
                                            </div>
                                            <x-status-badge :value="$payment->status" />

                                            {{-- Download PDF --}}
                                            <a href="{{ route('portal.balance.receipt', $payment) }}"
                                               class="inline-flex items-center gap-1.5 rounded-sm border border-primary/40 px-3 py-1.5 text-xs font-medium text-primary transition hover:bg-primary/10"
                                               title="Download PDF Receipt">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Download PDF
                                            </a>

                                            {{-- Email Receipt --}}
                                            <form action="{{ route('portal.balance.email-receipt', $payment) }}" method="POST"
                                                  onsubmit="return confirm('Send this receipt to your registered email address?')">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-sm border border-border px-3 py-1.5 text-xs font-medium text-muted-foreground transition hover:border-primary/40 hover:text-primary">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                    Email Receipt
                                                </button>
                                            </form>
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
