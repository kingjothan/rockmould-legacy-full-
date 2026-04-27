<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Support\SiteData;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        return view('admin.activity.index', [
            'company' => SiteData::company(),
            'logs' => ActivityLog::query()->with('user')->latest()->get(),
        ]);
    }
}
