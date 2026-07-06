<?php

namespace App\Mail;

use App\Models\Payment;
use App\Support\SiteData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Payment $payment) {}

    public function build(): self
    {
        $payment  = $this->payment;
        $assignment = $payment->assignment;
        $company  = SiteData::company();

        $pdf = Pdf::loadView('pdf.receipt', compact('payment', 'assignment', 'company'));
        $filename = 'Rockmould_Receipt_'.($payment->reference ?? 'RCK-'.$payment->id).'.pdf';

        return $this
            ->subject('Your Rockmould Payment Receipt — '.($payment->reference ?? 'RCK-'.$payment->id))
            ->view('emails.receipt')
            ->with(compact('payment', 'assignment', 'company'))
            ->attachData($pdf->output(), $filename, ['mime' => 'application/pdf']);
    }
}
