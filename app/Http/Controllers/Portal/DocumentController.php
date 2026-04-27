<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Support\AssetPath;
use App\Support\SiteData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Document::query()
            ->visibleToPortalUser(Auth::user())
            ->with('project', 'assignment.user');

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(fn ($builder) => $builder
                ->where('title', 'like', '%'.$search.'%')
                ->orWhere('category', 'like', '%'.$search.'%')
                ->orWhereHas('project', fn ($projectQuery) => $projectQuery->where('title', 'like', '%'.$search.'%')));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        return view('portal.documents', [
            'company' => SiteData::company(),
            'content' => SiteData::portalPage('documents'),
            'documents' => $query->libraryOrdered()->get(),
        ]);
    }

    public function download(Document $document): BinaryFileResponse
    {
        $document = Document::query()
            ->visibleToPortalUser(Auth::user())
            ->whereKey($document->getKey())
            ->firstOrFail();

        $path = AssetPath::localPath($document->file_path);
        abort_unless($path && is_file($path), 404);

        $extension = pathinfo((string) $path, PATHINFO_EXTENSION);
        $filename = Str::slug($document->title ?: 'document').($extension ? '.'.$extension : '');

        return response()->download($path, $filename);
    }
}
