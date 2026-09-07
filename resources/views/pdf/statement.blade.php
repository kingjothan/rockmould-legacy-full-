<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Statement — {{ $assignment->project?->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 13px; color: #1a1a1a; line-height: 1.6; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 36px; border-bottom: 2px solid #c6a96a; padding-bottom: 20px; }
        .brand { font-size: 22px; font-weight: 800; letter-spacing: 1px; color: #1f3a5f; }
        .brand-sub { font-size: 10px; text-transform: uppercase; letter-spacing: 3px; color: #888; margin-top: 2px; }
        .doc-title h1 { font-size: 26px; color: #1f3a5f; font-weight: 300; text-transform: uppercase; letter-spacing: 3px; text-align: left; }
        .doc-title p { font-size: 11px; color: #888; margin-top: 4px; text-align: left; }
        .info-grid { display: table; width: 100%; margin-bottom: 28px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-label { font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: #c6a96a; font-weight: 700; margin-bottom: 3px; margin-top: 12px; }
        .info-label:first-child { margin-top: 0; }
        .info-value { font-size: 13px; color: #333; }
        .summary-box { background: #faf8f5; border: 1px solid #e8e4de; border-left: 3px solid #c6a96a; padding: 16px 20px; margin-bottom: 28px; display: table; width: 100%; }
        .summary-cell { display: table-cell; width: 33.33%; text-align: center; }
        .summary-cell + .summary-cell { border-left: 1px solid #e8e4de; }
        .summary-label { font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: #999; margin-bottom: 4px; }
        .summary-amount { font-size: 18px; font-weight: 700; color: #1f3a5f; }
        .summary-amount.gold { color: #c6a96a; }
        table.payments { width: 100%; border-collapse: collapse; }
        table.payments th { background: #1f3a5f; color: #fff; font-size: 9px; text-transform: uppercase; letter-spacing: 1.5px; padding: 10px 12px; text-align: left; }
        table.payments td { padding: 10px 12px; border-bottom: 1px solid #f0ede7; font-size: 12px; vertical-align: middle; }
        table.payments tr:last-child td { border-bottom: none; }
        table.payments tr:nth-child(even) td { background: #faf8f5; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .status-paid { background: #e6f4ea; color: #1e7e34; }
        .status-pending { background: #fff8e1; color: #b8860b; }
        .status-overdue { background: #fde8e8; color: #c0392b; }
        .no-payments { text-align: center; padding: 28px; color: #999; font-size: 13px; border: 1px dashed #e0ddd6; }
        .footer { margin-top: 36px; padding-top: 18px; border-top: 1px solid #e8e4de; text-align: center; }
        .footer p { font-size: 10px; color: #999; line-height: 1.8; }
        .section-title { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #1f3a5f; font-weight: 700; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 1px solid #e8e4de; }
    </style>
</head>
<body>

    <div class="header">
        <div class="doc-title">
            <h1>Account Statement</h1>
            <p>Generated {{ now()->format('F j, Y') }}</p>
        </div>
        <div style="text-align: right;">
            @php($logoPath = public_path('images/rockmould_logo.png'))
            @if (file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="Rockmould Limited" style="height: 48px; width: auto;">
            @endif
        </div>
    </div>

    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Client</div>
            <div class="info-value">{{ $assignment->user?->name ?? 'Client' }}</div>
            <div class="info-label">Email</div>
            <div class="info-value">{{ $assignment->user?->email ?? '—' }}</div>
        </div>
        <div class="info-col" style="text-align: right;">
            <div class="info-label">Project</div>
            <div class="info-value">{{ $assignment->project?->title ?? '—' }}</div>
            @if ($assignment->unit?->label ?? $assignment->label ?? null)
            <div class="info-label">Unit</div>
            <div class="info-value">{{ $assignment->unit?->label ?? $assignment->label }}</div>
            @endif
            <div class="info-label">Status</div>
            <div class="info-value">{{ str($assignment->status)->title() }}</div>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-cell">
            <div class="summary-label">Contract Amount</div>
            <div class="summary-amount">NGN {{ number_format($assignment->contract_amount, 2) }}</div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Total Paid</div>
            <div class="summary-amount gold">NGN {{ number_format($assignment->amount_paid, 2) }}</div>
        </div>
        <div class="summary-cell">
            <div class="summary-label">Outstanding Balance</div>
            <div class="summary-amount">NGN {{ number_format($assignment->outstanding_balance, 2) }}</div>
        </div>
    </div>

    <div class="section-title">Payment History</div>

    @if ($assignment->payments->isNotEmpty())
        <table class="payments">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th style="text-align: right;">Amount (NGN)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assignment->payments as $payment)
                    <tr>
                        <td>{{ $payment->reference ?: 'RCK-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ ($payment->paid_at ?? $payment->due_at ?? $payment->created_at)?->format('M j, Y') }}</td>
                        <td>{{ $payment->payment_method ?: '—' }}</td>
                        <td><span class="status status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                        <td style="text-align: right; font-weight: 600;">{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @if ($payment->notes)
                        <tr>
                            <td colspan="5" style="font-size:11px;color:#888;padding-top:2px;padding-bottom:8px;">Note: {{ $payment->notes }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-payments">No payment records have been recorded for this project yet.</div>
    @endif

    <div class="footer">
        <p>
            {{ $company['name'] ?? 'Rockmould Limited' }}
            @if (!empty($company['address'])) &nbsp;·&nbsp; {{ $company['address'] }} @endif
            @if (!empty($company['phone'])) &nbsp;·&nbsp; {{ $company['phone'] }} @endif
            &nbsp;·&nbsp; {{ $company['email'] ?? 'info@rockmould.com' }}
        </p>
        <p style="margin-top: 8px;">This is a computer-generated statement and does not require a signature.</p>
    </div>

</body>
</html>
