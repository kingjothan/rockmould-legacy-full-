<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ConsultationBooking;
use App\Models\ContactSubmission;
use App\Models\KycRequest;
use App\Models\Payment;
use App\Models\ProjectAssignment;
use App\Models\SupportTicket;
use App\Models\User;
use App\Support\SiteData;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'company' => SiteData::company(),
            'stats' => [
                'clients' => User::query()->where('role', 'client')->count(),
                'projects' => ProjectAssignment::query()->where('status', 'active')->count(),
                'kyc' => KycRequest::query()->where('status', 'pending')->count(),
                'outstanding' => ProjectAssignment::query()->sum('outstanding_balance'),
            ],
            'recentActivity' => ActivityLog::query()->with('user')->latest()->take(8)->get(),
            'notifications' => [
                ['label' => 'Pending consultations', 'count' => Schema::hasTable('consultation_bookings') ? ConsultationBooking::query()->where('status', ConsultationBooking::STATUS_PENDING)->count() : 0],
                ['label' => 'Pending KYC requests', 'count' => KycRequest::query()->where('status', 'pending')->count()],
                ['label' => 'Overdue payments', 'count' => Payment::query()->where('status', 'overdue')->count()],
                ['label' => 'Open support tickets', 'count' => SupportTicket::query()->where('status', 'open')->count()],
                ['label' => 'New contact forms', 'count' => ContactSubmission::query()->where('status', 'new')->count()],
            ],
        ]);
    }
}
