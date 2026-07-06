<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\ReceiptMail;
use App\Models\Payment;
use App\Support\SiteData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BalanceController extends Controller
{
    public function index(): View
    {
        $assignments = Auth::user()->assignments()->with(['project', 'unit', 'payments'])->get();

        return view('portal.balance', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('balance'),
            'assignments' => $assignments,
            'totals' => [
                'contract' => $assignments->sum('contract_amount'),
                'paid' => $assignments->sum('amount_paid'),
                'outstanding' => $assignments->sum('outstanding_balance'),
            ],
        ]);
    }

    public function receipt(Payment $payment)
    {
        $payment->load('assignment.project', 'assignment.user', 'assignment.unit');
        $assignment = $payment->assignment;

        abort_unless($assignment && (int) $assignment->user_id === (int) Auth::id(), 403);

        $pdf = Pdf::loadView('pdf.receipt', [
            'payment' => $payment,
            'assignment' => $assignment,
            'company' => SiteData::company(),
        ]);

        $filename = 'Rockmould_Receipt_'.($payment->reference ?? 'RCK-'.$payment->id).'.pdf';

        return $pdf->download($filename);
    }

    public function statement(\App\Models\ProjectAssignment $assignment)
    {
        abort_unless((int) $assignment->user_id === (int) Auth::id(), 403);

        $assignment->load(['project', 'user', 'unit', 'payments']);

        $pdf = Pdf::loadView('pdf.statement', [
            'assignment' => $assignment,
            'company'    => SiteData::company(),
        ]);

        $slug = str($assignment->project?->title ?? 'Project')->slug()->toString();
        $filename = 'Rockmould_Statement_'.$slug.'_'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }

    public function emailReceipt(Payment $payment): RedirectResponse
    {
        $payment->load('assignment.project', 'assignment.user', 'assignment.unit');
        $assignment = $payment->assignment;

        abort_unless($assignment && (int) $assignment->user_id === (int) Auth::id(), 403);

        $recipient = $assignment->user->email;

        Mail::to($recipient)->send(new ReceiptMail($payment));

        return back()->with('success', 'Receipt sent to '.$recipient.'.');
    }
}
