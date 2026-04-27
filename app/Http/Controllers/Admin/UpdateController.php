<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UpdateController extends Controller
{
    public function index(): View
    {
        return view('admin.updates.index', [
            'company' => SiteData::company(),
            'updates' => ProjectUpdate::query()->with('project')->latest('update_date')->get(),
            'projects' => Project::query()->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'update_date' => ['required', 'date'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'visible_to_client' => ['nullable', 'boolean'],
        ]);

        $update = ProjectUpdate::query()->create([
            ...$data,
            'attachments' => [],
            'visible_to_client' => $request->boolean('visible_to_client', true),
        ]);

        Activity::log('update.created', 'Project update created.', $request->user(), $update);

        return back()->with('success', 'Project update published.');
    }
}
