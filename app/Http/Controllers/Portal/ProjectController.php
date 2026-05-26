<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\MediaAsset;
use App\Models\ProjectAssignment;
use App\Support\SiteData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Auth::user()->assignments()->with(['project', 'unit']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $assignments = $query->latest()->get();

        return view('portal.projects.index', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('projects'),
            'assignments' => $assignments,
            'projectCount' => $assignments->pluck('project_id')->filter()->unique()->count(),
            'unitCount' => $assignments->count(),
        ]);
    }

    public function show(ProjectAssignment $assignment): View
    {
        abort_unless($assignment->user_id === Auth::id(), 403);

        $assignment->load(['project.milestones', 'project.updates', 'payments', 'unit']);
        abort_unless($assignment->project, 404);

        $galleryMedia = MediaAsset::query()
            ->visibleToPortalAssignment($assignment)
            ->with('project', 'assignment.user')
            ->libraryOrdered()
            ->get();
        $portalDocuments = Document::query()
            ->visibleToPortalAssignment($assignment)
            ->with('project', 'assignment.user')
            ->libraryOrdered()
            ->get();

        return view('portal.projects.show', [
            'company' => SiteData::company(),
            'assignment' => $assignment,
            'galleryMedia' => $galleryMedia,
            'portalDocuments' => $portalDocuments,
        ]);
    }
}
