<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ProjectAssignment;
use App\Support\Activity;
use App\Support\SiteData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        return view('admin.payments.index', [
            'company' => SiteData::company(),
            'payments' => Payment::query()->with('assignment.project', 'assignment.user', 'assignment.unit')->latest('paid_at')->latest()->get(),
            'assignments' => ProjectAssignment::query()->with('project', 'user', 'unit')->orderByDesc('created_at')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_assignment_id' => ['required', 'integer', 'exists:project_assignments,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'due_at' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $payment = Payment::query()->create($data);
        $assignment = $payment->assignment;

        if ($assignment) {
            if ($payment->status === 'paid') {
                $assignment->amount_paid = (float) $assignment->amount_paid + (float) $payment->amount;
            }

            $assignment->outstanding_balance = max(0, (float) $assignment->contract_amount - (float) $assignment->amount_paid);
            $assignment->save();
        }

        Activity::log('payment.created', 'Payment record added.', $request->user(), $payment);

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function updateAssignmentFinancials(Request $request, ProjectAssignment $assignment): RedirectResponse
    {
        $data = $request->validate([
            'contract_amount' => ['required', 'numeric', 'min:0'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'outstanding_balance' => ['nullable', 'numeric', 'min:0'],
            'next_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'next_payment_due_at' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $data['outstanding_balance'] = $data['outstanding_balance'] ?? max(0, (float) $data['contract_amount'] - (float) $data['amount_paid']);

        $assignment->update($data);

        Activity::log('assignment.financials.updated', 'Client assignment financials updated.', $request->user(), $assignment);

        return back()->with('success', 'Client financial details updated.');
    }

    public function receipt(Payment $payment)
    {
        $payment->load('assignment.project', 'assignment.user', 'assignment.unit');
        $assignment = $payment->assignment;

        $pdf = Pdf::loadView('pdf.receipt', [
            'payment' => $payment,
            'assignment' => $assignment,
            'company' => SiteData::company(),
        ]);

        $filename = 'Rockmould_Receipt_'.($payment->reference ?? 'RCK-'.$payment->id).'.pdf';

        return $pdf->download($filename);
    }
}
