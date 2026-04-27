<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ProjectUpdate;
use App\Support\SiteData;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UpdateController extends Controller
{
    public function index(): View
    {
        $projectIds = Auth::user()->assignments()->pluck('project_id');

        return view('portal.updates', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('updates'),
            'updates' => ProjectUpdate::query()->whereIn('project_id', $projectIds)->where('visible_to_client', true)->with('project')->latest('update_date')->get(),
        ]);
    }
}
