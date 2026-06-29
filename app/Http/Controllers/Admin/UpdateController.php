<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Support\Activity;
use App\Support\PublicStorage;
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
            'attachment_uploads' => ['nullable', 'array'],
            'attachment_uploads.*' => ['file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'attachment_urls' => ['nullable', 'string'],
        ]);

        $attachments = [];

        foreach ($request->file('attachment_uploads', []) as $file) {
            $path = PublicStorage::store($file, 'updates');
            if ($path) $attachments[] = $path;
        }

        if (!empty($data['attachment_urls'])) {
            foreach (preg_split('/\r?\n/', $data['attachment_urls']) as $url) {
                $url = trim($url);
                if ($url) $attachments[] = $url;
            }
        }

        $update = ProjectUpdate::query()->create([
            'project_id' => $data['project_id'],
            'title' => $data['title'],
            'body' => $data['body'],
            'update_date' => $data['update_date'],
            'progress' => $data['progress'] ?? null,
            'attachments' => $attachments,
            'visible_to_client' => $request->boolean('visible_to_client', true),
        ]);

        Activity::log('update.created', 'Project update created.', $request->user(), $update);

        return back()->with('success', 'Project update published.');
    }
}
