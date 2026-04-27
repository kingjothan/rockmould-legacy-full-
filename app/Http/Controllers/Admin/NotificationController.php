<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationBooking;
use App\Models\ContactSubmission;
use App\Models\KycRequest;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Support\SiteData;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('admin.notifications.index', [
            'company' => SiteData::company(),
            'notifications' => collect([
                ['title' => 'Pending consultation requests', 'count' => Schema::hasTable('consultation_bookings') ? ConsultationBooking::query()->where('status', ConsultationBooking::STATUS_PENDING)->count() : 0, 'tone' => 'status-pending'],
                ['title' => 'Pending KYC requests', 'count' => KycRequest::query()->where('status', 'pending')->count(), 'tone' => 'status-pending'],
                ['title' => 'Overdue payments', 'count' => Payment::query()->where('status', 'overdue')->count(), 'tone' => 'status-pending'],
                ['title' => 'Open support tickets', 'count' => SupportTicket::query()->where('status', 'open')->count(), 'tone' => 'status-active'],
                ['title' => 'New contact submissions', 'count' => ContactSubmission::query()->where('status', 'new')->count(), 'tone' => 'status-active'],
            ]),
        ]);
    }
}
