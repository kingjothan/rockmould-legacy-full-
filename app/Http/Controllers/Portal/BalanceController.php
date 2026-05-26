<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Support\SiteData;
use Illuminate\Support\Facades\Auth;
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
}
