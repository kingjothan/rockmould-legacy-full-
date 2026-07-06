<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Payment Receipt</title>
<style>
  body { margin: 0; padding: 0; background: #f5f4f0; font-family: 'Helvetica Neue', Arial, sans-serif; color: #1a1a1a; }
  .wrap { max-width: 560px; margin: 32px auto; background: #fff; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
  .head { background: #1f3a5f; padding: 36px 40px 28px; }
  .head-brand { font-size: 20px; font-weight: 800; letter-spacing: 2px; color: #fff; }
  .head-sub { font-size: 9px; letter-spacing: 4px; text-transform: uppercase; color: #c6a96a; margin-top: 2px; }
  .head-title { margin-top: 20px; font-size: 13px; color: rgba(255,255,255,.7); }
  .body { padding: 36px 40px; }
  .greeting { font-size: 17px; font-weight: 600; color: #1f3a5f; margin-bottom: 12px; }
  .copy { font-size: 14px; color: #555; line-height: 1.7; margin-bottom: 20px; }
  .card { background: #faf8f5; border-left: 3px solid #c6a96a; border-radius: 2px; padding: 20px 24px; margin-bottom: 24px; }
  .card-row { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; margin-bottom: 8px; }
  .card-row:last-child { margin-bottom: 0; }
  .card-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #999; }
  .card-value { font-size: 14px; color: #1a1a1a; font-weight: 600; text-align: right; }
  .amount { font-size: 22px; color: #1f3a5f; font-weight: 700; }
  .note { font-size: 13px; color: #777; line-height: 1.6; margin-bottom: 24px; }
  .divider { border: none; border-top: 1px solid #e8e4de; margin: 24px 0; }
  .footer { background: #f5f4f0; padding: 24px 40px; text-align: center; }
  .footer p { font-size: 11px; color: #999; line-height: 1.8; margin: 0; }
  .footer a { color: #c6a96a; text-decoration: none; }
</style>
</head>
<body>
<div class="wrap">
  <div class="head">
    <div class="head-brand">ROCKMOULD</div>
    <div class="head-sub">Limited</div>
    <div class="head-title">Payment Receipt</div>
  </div>

  <div class="body">
    <p class="greeting">Dear {{ $assignment->user->name ?? 'Client' }},</p>
    <p class="copy">
      Thank you for your payment. Please find your official receipt attached to this email as a PDF.
      A summary of the payment is shown below for your reference.
    </p>

    <div class="card">
      <div class="card-row">
        <span class="card-label">Reference</span>
        <span class="card-value">{{ $payment->reference ?? 'RCK-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
      </div>
      <div class="card-row">
        <span class="card-label">Project</span>
        <span class="card-value">{{ $assignment->project->title ?? 'N/A' }}</span>
      </div>
      @if ($assignment->unit?->label ?? $assignment->label ?? null)
      <div class="card-row">
        <span class="card-label">Unit</span>
        <span class="card-value">{{ $assignment->unit?->label ?? $assignment->label }}</span>
      </div>
      @endif
      <div class="card-row">
        <span class="card-label">Date</span>
        <span class="card-value">{{ ($payment->paid_at ?? $payment->created_at)->format('F j, Y') }}</span>
      </div>
      @if ($payment->payment_method)
      <div class="card-row">
        <span class="card-label">Method</span>
        <span class="card-value">{{ $payment->payment_method }}</span>
      </div>
      @endif
      <hr style="border:none;border-top:1px solid #e8e4de;margin:14px 0;">
      <div class="card-row">
        <span class="card-label">Amount Paid</span>
        <span class="card-value amount">NGN {{ number_format($payment->amount, 2) }}</span>
      </div>
    </div>

    <p class="note">
      Your updated outstanding balance is
      <strong>NGN {{ number_format($assignment->outstanding_balance, 2) }}</strong>.
      Log in to your client portal at any time to view your full payment history and download previous receipts.
    </p>

    @if ($payment->notes)
    <p class="note"><strong>Note:</strong> {{ $payment->notes }}</p>
    @endif

    <hr class="divider">
    <p class="copy" style="margin:0;">
      If you have any questions about this receipt or your account, please do not hesitate to contact us at
      <a href="mailto:{{ $company['email'] ?? 'info@rockmould.com' }}" style="color:#c6a96a;">{{ $company['email'] ?? 'info@rockmould.com' }}</a>
      or call <strong>{{ $company['phone'] ?? '' }}</strong>.
    </p>
  </div>

  <div class="footer">
    <p>
      {{ $company['name'] ?? 'Rockmould Limited' }}<br>
      {{ $company['address'] ?? '' }}<br>
      <a href="{{ url('/') }}">{{ url('/') }}</a>
    </p>
    <p style="margin-top:8px;">This is an automated email. Please do not reply directly to this message.</p>
  </div>
</div>
</body>
</html>
