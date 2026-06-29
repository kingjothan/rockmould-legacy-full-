<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt — {{ $payment->reference ?? 'RCK-'.$payment->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 13px; color: #1a1a1a; line-height: 1.6; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 2px solid #c6a96a; padding-bottom: 20px; }
        .brand { font-size: 22px; font-weight: 800; letter-spacing: 1px; color: #1f3a5f; }
        .brand-sub { font-size: 10px; text-transform: uppercase; letter-spacing: 3px; color: #888; margin-top: 2px; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 28px; color: #1f3a5f; font-weight: 300; text-transform: uppercase; letter-spacing: 3px; }
        .doc-title p { font-size: 11px; color: #888; margin-top: 4px; }
        .info-grid { display: table; width: 100%; margin-bottom: 30px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-label { font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: #c6a96a; font-weight: 700; margin-bottom: 4px; }
        .info-value { font-size: 13px; color: #333; margin-bottom: 12px; }
        table.details { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.details th { background: #1f3a5f; color: #fff; font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; padding: 10px 14px; text-align: left; }
        table.details td { padding: 12px 14px; border-bottom: 1px solid #e8e4de; font-size: 13px; }
        table.details tr:last-child td { border-bottom: 2px solid #c6a96a; }
        .total-row { background: #faf8f5; }
        .total-row td { font-weight: 700; font-size: 15px; color: #1f3a5f; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .status-paid { background: #e6f4ea; color: #1e7e34; }
        .status-pending { background: #fff8e1; color: #b8860b; }
        .status-overdue { background: #fde8e8; color: #c0392b; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e8e4de; text-align: center; }
        .footer p { font-size: 10px; color: #999; line-height: 1.8; }
        .notes { background: #faf8f5; border-left: 3px solid #c6a96a; padding: 14px 18px; margin-bottom: 30px; font-size: 12px; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">ROCKMOULD</div>
            <div class="brand-sub">Limited</div>
        </div>
        <div class="doc-title">
            <h1>Receipt</h1>
            <p>{{ $payment->reference ?? 'RCK-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-col">
            <div class="info-label">Issued To</div>
            <div class="info-value">
                {{ $assignment->user->name ?? 'Client' }}<br>
                {{ $assignment->user->email ?? '' }}
            </div>

            <div class="info-label">Project</div>
            <div class="info-value">
                {{ $assignment->project->title ?? 'N/A' }}<br>
                {{ $assignment->unit?->label ?? $assignment->label ?? '' }}
            </div>
        </div>
        <div class="info-col" style="text-align: right;">
            <div class="info-label">Date</div>
            <div class="info-value">{{ $payment->paid_at?->format('F j, Y') ?? $payment->created_at->format('F j, Y') }}</div>

            <div class="info-label">Payment Method</div>
            <div class="info-value">{{ $payment->payment_method ?? 'Not specified' }}</div>

            <div class="info-label">Status</div>
            <div class="info-value">
                <span class="status status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
            </div>
        </div>
    </div>

    <table class="details">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Payment for {{ $assignment->project->title ?? 'Project' }}
                    @if ($assignment->unit?->label ?? $assignment->label)
                        — {{ $assignment->unit?->label ?? $assignment->label }}
                    @endif
                </td>
                <td style="text-align: right;">NGN {{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Paid</td>
                <td style="text-align: right;">NGN {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if ($payment->notes)
        <div class="notes">
            <strong>Notes:</strong> {{ $payment->notes }}
        </div>
    @endif

    <table class="details">
        <thead>
            <tr>
                <th>Account Summary</th>
                <th style="text-align: right;">Amount (NGN)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Contract Amount</td>
                <td style="text-align: right;">{{ number_format($assignment->contract_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Total Amount Paid</td>
                <td style="text-align: right;">{{ number_format($assignment->amount_paid, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Outstanding Balance</td>
                <td style="text-align: right;">{{ number_format($assignment->outstanding_balance, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>
            {{ $company['name'] ?? 'Rockmould Limited' }}<br>
            {{ $company['address'] ?? '' }}<br>
            {{ $company['phone'] ?? '' }} | {{ $company['email'] ?? 'info@rockmould.com' }}
        </p>
        <p style="margin-top: 10px;">This is a computer-generated receipt and does not require a signature.</p>
    </div>
</body>
</html>
