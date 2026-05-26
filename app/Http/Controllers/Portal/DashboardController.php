<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\MediaAsset;
use App\Models\ProjectUpdate;
use App\Support\SiteData;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $assignments = $user->assignments()->with(['project', 'unit'])->get();
        $assignmentIds = $assignments->pluck('id');
        $projectIds = $assignments->pluck('project_id');

        return view('portal.dashboard', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('dashboard'),
            'assignments' => $assignments,
            'totals' => [
                'projects' => $assignments->pluck('project_id')->filter()->unique()->count(),
                'units' => $assignments->count(),
                'active' => $assignments->where('status', 'active')->count(),
                'paid' => $assignments->sum('amount_paid'),
                'outstanding' => $assignments->sum('outstanding_balance'),
            ],
            'recentDocuments' => Document::query()->visibleToPortalUser($user)->latest()->take(4)->get(),
            'recentMedia' => MediaAsset::query()->visibleToPortalUser($user)->latest()->take(4)->get(),
            'recentUpdates' => ProjectUpdate::query()->whereIn('project_id', $projectIds)->where('visible_to_client', true)->latest('update_date')->take(4)->get(),
        ]);
    }
}
