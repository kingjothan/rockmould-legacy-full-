<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Models\Project;
use App\Support\AssetPath;
use App\Support\SiteData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $projectIds = $user->assignments()->pluck('project_id')->filter()->unique();
        $baseQuery = MediaAsset::query()
            ->visibleToPortalUser($user)
            ->with('project', 'assignment.user');

        $projects = Project::query()
            ->whereIn('id', $projectIds)
            ->orderBy('title')
            ->get(['id', 'title']);

        $query = clone $baseQuery;

        if ($request->filled('type') && $request->string('type')->toString() !== 'all') {
            $query->where('media_type', $request->string('type')->toString());
        }

        if ($request->filled('project') && $request->string('project')->toString() !== 'all') {
            $query->where('project_id', $request->integer('project'));
        }

        $media = $query->orderByDesc('is_featured')->orderBy('sort_order')->latest('id')->get();

        return view('portal.media', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('media'),
            'media' => $media,
            'projects' => $projects,
            'activeMedia' => $request->filled('preview') ? $media->firstWhere('id', (int) $request->integer('preview')) : null,
        ]);
    }

    public function download(MediaAsset $mediaAsset): BinaryFileResponse
    {
        $mediaAsset = MediaAsset::query()
            ->visibleToPortalUser(Auth::user())
            ->whereKey($mediaAsset->getKey())
            ->firstOrFail();

        $path = AssetPath::localPath($mediaAsset->file_path);
        abort_unless($path && is_file($path), 404);

        $extension = pathinfo((string) $path, PATHINFO_EXTENSION);
        $filename = Str::slug($mediaAsset->title ?: 'media-asset').($extension ? '.'.$extension : '');

        return response()->download($path, $filename);
    }
}
