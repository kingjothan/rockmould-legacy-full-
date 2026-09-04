<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrochureDownload;
use App\Models\Project;

class BrochureDownloadController extends Controller
{
    public function index()
    {
        $downloads = BrochureDownload::with('project')
            ->latest()
            ->paginate(50);

        $projects = Project::whereNotNull('brochure_path')->get(['id', 'title']);

        return view('admin.brochure-downloads', compact('downloads', 'projects'));
    }
}
