<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use App\Models\PartnershipInquiry;
use App\Models\SupportTicket;
use App\Support\SiteData;
use Illuminate\View\View;

class FormController extends Controller
{
    public function index(): View
    {
        return view('admin.forms.index', [
            'company' => SiteData::company(),
            'contacts' => ContactSubmission::query()->latest()->get(),
            'partnerships' => PartnershipInquiry::query()->latest()->get(),
            'supportTickets' => SupportTicket::query()->with('user', 'assignment.project')->latest()->get(),
            'newsletters' => NewsletterSubscriber::query()->latest()->get(),
        ]);
    }
}
