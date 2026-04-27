<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\MediaAsset;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\User;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query()->withCount('assignments');

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(fn ($builder) => $builder->where('title', 'like', '%'.$search.'%')->orWhere('location', 'like', '%'.$search.'%'));
        }

        return view('admin.projects.index', [
            'company' => SiteData::company(),
            'projects' => $query->latest()->get(),
            'clients' => User::query()->where('role', 'client')->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function show(Project $project): View
    {
        return view('admin.projects.show', [
            'company' => SiteData::company(),
            'project' => $project->load(['assignments.user', 'updates', 'milestones', 'documentsRelation', 'mediaAssets']),
            'clients' => User::query()->where('role', 'client')->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProject($request);
        $project = Project::query()->create($data);
        $this->storeUploadedProjectMedia($request, $project);
        $this->storeUploadedProjectDocuments($request, $project);
        Activity::log('project.created', 'Project created in admin.', $request->user(), $project);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project created successfully.');
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validatedProject($request, $project));
        $this->storeUploadedProjectMedia($request, $project);
        $this->storeUploadedProjectDocuments($request, $project);
        Activity::log('project.updated', 'Project updated in admin.', $request->user(), $project);

        return back()->with('success', 'Project updated.');
    }

    public function storeAssignment(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'unit_label' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'contract_amount' => ['required', 'numeric', 'min:0'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'next_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'next_payment_due_at' => ['nullable', 'date'],
        ]);

        $assignment = ProjectAssignment::query()->create([
            ...$data,
            'project_id' => $project->id,
            'reference' => 'RM-'.now()->format('Y').'-'.str_pad((string) (ProjectAssignment::query()->count() + 1), 4, '0', STR_PAD_LEFT),
            'outstanding_balance' => max(0, (float) $data['contract_amount'] - (float) $data['amount_paid']),
            'account_manager_name' => $request->user()->name,
            'account_manager_email' => $request->user()->email,
            'account_manager_phone' => $request->user()->phone,
        ]);

        Activity::log('assignment.created', 'Project assignment created.', $request->user(), $assignment);

        return back()->with('success', 'Client assignment added to project.');
    }

    private function validatedProject(Request $request, ?Project $project = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
            'overview' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'cover_image_upload' => ['nullable', 'file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'launch_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date'],
            'features_text' => ['nullable', 'string'],
            'gallery_text' => ['nullable', 'string'],
            'highlights_json' => ['nullable', 'string'],
            'media_uploads' => ['nullable', 'array'],
            'media_uploads.*' => ['file', 'max:204800', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm,mov,ogg'],
            'document_uploads' => ['nullable', 'array'],
            'document_uploads.*' => ['file', 'max:20480'],
            'document_category' => ['nullable', 'string', 'max:255'],
            'document_version' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['nullable', 'boolean'],
            'is_portfolio' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ];

        if (MediaAsset::supportsGallerySections()) {
            $rules['uploaded_media_gallery_section'] = ['nullable', Rule::in(array_keys(MediaAsset::gallerySectionOptions()))];
        }

        $data = $request->validate($rules);

        $coverImage = ($data['cover_image'] ?? null) ?: $project?->cover_image;

        if ($request->hasFile('cover_image_upload')) {
            $coverImage = $request->file('cover_image_upload')->store('projects/covers', 'public');

            if ($project?->cover_image && $project->cover_image !== $coverImage) {
                $this->deleteStoredAsset($project->cover_image);
            }
        }

        return [
            'title' => $data['title'],
            'slug' => ($data['slug'] ?? null) ?: Str::slug($data['title']),
            'location' => $data['location'],
            'category' => $data['category'],
            'status' => strtolower($data['status']),
            'overview' => $data['overview'],
            'description' => $data['description'] ?? null,
            'cover_image' => $coverImage,
            'progress' => $data['progress'],
            'budget' => $data['budget'] ?? null,
            'price_from' => $data['price_from'] ?? null,
            'launch_date' => $data['launch_date'] ?? null,
            'completion_date' => $data['completion_date'] ?? null,
            'features' => $this->lines($data['features_text'] ?? ''),
            'gallery' => $this->lines($data['gallery_text'] ?? ''),
            'highlights' => $data['highlights_json'] ? json_decode($data['highlights_json'], true) : ($project?->highlights ?? []),
            'is_featured' => $request->boolean('is_featured'),
            'is_portfolio' => $request->boolean('is_portfolio'),
            'is_published' => $request->boolean('is_published', true),
        ];
    }

    private function lines(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: [])));
    }

    private function storeUploadedProjectMedia(Request $request, Project $project): void
    {
        $files = collect($request->file('media_uploads', []))
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->values();

        if ($files->isEmpty()) {
            return;
        }

        $visibleToClient = $request->boolean('uploaded_media_visible_to_client', true);
        $gallerySection = $request->string('uploaded_media_gallery_section')->toString() ?: MediaAsset::SECTION_GENERAL;
        $baseSortOrder = ((int) MediaAsset::query()->where('project_id', $project->id)->max('sort_order')) + 1;
        $hasFeaturedMedia = MediaAsset::query()->where('project_id', $project->id)->where('is_featured', true)->exists();

        foreach ($files as $index => $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $isVideo = in_array($extension, ['mp4', 'webm', 'mov', 'ogg'], true);
            $path = $file->store($isVideo ? 'media/videos' : 'media/images', 'public');

            $payload = [
                'project_id' => $project->id,
                'project_assignment_id' => null,
                'uploaded_by' => $request->user()->id,
                'title' => $this->generatedAssetTitle($project->title, $file),
                'media_type' => $isVideo ? 'video' : 'image',
                'file_path' => $path,
                'thumbnail_path' => $isVideo ? null : $path,
                'notes' => 'Uploaded during project setup.',
                'sort_order' => $baseSortOrder + $index,
                'is_featured' => ! $hasFeaturedMedia && $index === 0,
                'visible_to_client' => $visibleToClient,
            ];

            if (MediaAsset::supportsGallerySections()) {
                $payload['gallery_section'] = $gallerySection;
            }

            MediaAsset::query()->create($payload);
        }
    }

    private function storeUploadedProjectDocuments(Request $request, Project $project): void
    {
        $files = collect($request->file('document_uploads', []))
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->values();

        if ($files->isEmpty()) {
            return;
        }

        $visibleToClient = $request->boolean('uploaded_documents_visible_to_client', true);
        $category = $request->string('document_category')->trim()->toString() ?: 'Project Document';
        $version = $request->string('document_version')->trim()->toString() ?: 'v1';

        foreach ($files as $file) {
            $path = $file->store('documents', 'public');

            Document::query()->create([
                'project_id' => $project->id,
                'project_assignment_id' => null,
                'user_id' => null,
                'uploaded_by' => $request->user()->id,
                'title' => $this->generatedAssetTitle($project->title, $file),
                'category' => $category,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'version' => $version,
                'notes' => 'Uploaded during project setup.',
                'visible_to_client' => $visibleToClient,
                'is_archived' => false,
            ]);
        }
    }

    private function generatedAssetTitle(string $projectTitle, UploadedFile $file): string
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $cleaned = Str::of($filename)
            ->replace(['_', '-'], ' ')
            ->squish()
            ->title()
            ->toString();

        return trim($projectTitle.' - '.$cleaned, ' -');
    }

    private function deleteStoredAsset(?string $path): void
    {
        if (! $path) {
            return;
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'images/') || str_starts_with($normalized, 'storage/')) {
            return;
        }

        Storage::disk('public')->delete($normalized);
    }
}
