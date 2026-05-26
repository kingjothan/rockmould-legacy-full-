<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\MediaAsset;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\Unit;
use App\Models\User;
use App\Support\Activity;
use App\Support\PublicStorage;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query()->withCount(['assignments', 'units']);

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
            'project' => $project->load(['assignments.user', 'assignments.unit', 'units.client', 'updates', 'milestones', 'documentsRelation', 'mediaAssets']),
            'clients' => User::query()->where('role', 'client')->where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProject($request);
        $project = Project::query()->create($data);
        $this->storeUploadedProjectMedia($request, $project);
        $this->storeLinkedProjectMedia($request, $project);
        $this->storeUploadedProjectDocuments($request, $project);
        Activity::log('project.created', 'Project created in admin.', $request->user(), $project);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project created successfully.');
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validatedProject($request, $project));
        $this->storeUploadedProjectMedia($request, $project);
        $this->storeLinkedProjectMedia($request, $project);
        $this->storeUploadedProjectDocuments($request, $project);
        Activity::log('project.updated', 'Project updated in admin.', $request->user(), $project);

        return back()->with('success', 'Project updated.');
    }

    public function storeAssignment(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'client'))],
            'unit_label' => ['nullable', 'required_without:unit_labels', 'string', 'max:255'],
            'unit_labels' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'contract_amount' => ['required', 'numeric', 'min:0'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'next_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'next_payment_due_at' => ['nullable', 'date'],
        ]);

        $unitLabels = collect(preg_split('/\r\n|\r|\n/', (string) ($data['unit_labels'] ?? '')) ?: [])
            ->map(fn (string $label) => trim($label))
            ->filter()
            ->when(empty($data['unit_labels'] ?? null), fn ($labels) => $labels->push($data['unit_label']))
            ->unique()
            ->values();

        $created = 0;

        DB::transaction(function () use ($unitLabels, $project, $data, $request, &$created): void {
            foreach ($unitLabels as $unitLabel) {
                $unit = $this->syncUnit($project, $unitLabel, $data);
                $assignment = ProjectAssignment::query()->create($this->assignmentPayload($project, $unit, $unitLabel, $data, $request));

                Activity::log('assignment.created', 'Project assignment created.', $request->user(), $assignment);
                $created++;
            }
        });

        return back()->with('success', $created === 1 ? 'Client assignment added to project.' : $created.' client unit assignments added to project.');
    }

    public function updateAssignment(Request $request, Project $project, ProjectAssignment $assignment): RedirectResponse
    {
        abort_unless($assignment->project_id === $project->id, 404);

        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'client'))],
            'unit_label' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'contract_amount' => ['required', 'numeric', 'min:0'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'next_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'next_payment_due_at' => ['nullable', 'date'],
            'client_notes' => ['nullable', 'string'],
            'internal_notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($project, $assignment, $data): void {
            $unit = $this->syncUnit($project, $data['unit_label'], $data);

            $assignment->update([
                'user_id' => $data['user_id'],
                'unit_id' => $unit->id,
                'unit_label' => $data['unit_label'],
                'status' => $data['status'],
                'progress_percentage' => $data['progress_percentage'],
                'contract_amount' => $data['contract_amount'],
                'amount_paid' => $data['amount_paid'],
                'outstanding_balance' => max(0, (float) $data['contract_amount'] - (float) $data['amount_paid']),
                'next_payment_amount' => $data['next_payment_amount'] ?? null,
                'next_payment_due_at' => $data['next_payment_due_at'] ?? null,
                'client_notes' => $data['client_notes'] ?? null,
                'internal_notes' => $data['internal_notes'] ?? null,
            ]);
        });

        Activity::log('assignment.updated', 'Client project ownership updated.', $request->user(), $assignment);

        return back()->with('success', 'Client project ownership updated.');
    }

    private function syncUnit(Project $project, string $unitLabel, array $data): Unit
    {
        return Unit::query()->updateOrCreate(
            ['project_id' => $project->id, 'label' => $unitLabel],
            [
                'client_id' => $data['user_id'],
                'status' => $data['status'],
                'price' => $data['contract_amount'],
            ],
        );
    }

    private function assignmentPayload(Project $project, Unit $unit, string $unitLabel, array $data, Request $request): array
    {
        return [
            'user_id' => $data['user_id'],
            'unit_label' => $unitLabel,
            'status' => $data['status'],
            'progress_percentage' => $data['progress_percentage'],
            'contract_amount' => $data['contract_amount'],
            'amount_paid' => $data['amount_paid'],
            'next_payment_amount' => $data['next_payment_amount'] ?? null,
            'next_payment_due_at' => $data['next_payment_due_at'] ?? null,
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'reference' => 'RM-'.now()->format('Y').'-'.str_pad((string) (ProjectAssignment::query()->count() + 1), 4, '0', STR_PAD_LEFT),
            'outstanding_balance' => max(0, (float) $data['contract_amount'] - (float) $data['amount_paid']),
            'account_manager_name' => $request->user()->name,
            'account_manager_email' => $request->user()->email,
            'account_manager_phone' => $request->user()->phone,
        ];
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
            'cover_image' => ['nullable', 'string', 'max:2048'],
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
            'media_urls_text' => ['nullable', 'string'],
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

        $coverImage = $this->syncMediaInput($request, 'cover_image', 'cover_image_upload', 'projects/covers', $project?->cover_image, 'remove_cover_image');

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
            $path = PublicStorage::store($file, $isVideo ? 'media/videos' : 'media/images');

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

    private function storeLinkedProjectMedia(Request $request, Project $project): void
    {
        $links = $this->lines((string) $request->input('media_urls_text', ''));

        if ($links === []) {
            return;
        }

        $visibleToClient = $request->boolean('uploaded_media_visible_to_client', true);
        $gallerySection = $request->string('uploaded_media_gallery_section')->toString() ?: MediaAsset::SECTION_GENERAL;
        $baseSortOrder = ((int) MediaAsset::query()->where('project_id', $project->id)->max('sort_order')) + 1;
        $hasFeaturedMedia = MediaAsset::query()->where('project_id', $project->id)->where('is_featured', true)->exists();

        foreach ($links as $index => $link) {
            $mediaType = $this->mediaTypeForPath($link);

            $payload = [
                'project_id' => $project->id,
                'project_assignment_id' => null,
                'uploaded_by' => $request->user()->id,
                'title' => $this->generatedAssetTitleFromPath($project->title, $link),
                'media_type' => $mediaType,
                'file_path' => $link,
                'thumbnail_path' => $mediaType === 'image' ? $link : null,
                'notes' => 'Linked from CDN or external media URL during project setup.',
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
            $path = PublicStorage::store($file, 'documents');

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

    private function generatedAssetTitleFromPath(string $projectTitle, string $path): string
    {
        $filename = pathinfo((string) parse_url($path, PHP_URL_PATH), PATHINFO_FILENAME);
        $cleaned = Str::of($filename ?: 'Linked Media')
            ->replace(['_', '-'], ' ')
            ->squish()
            ->title()
            ->toString();

        return trim($projectTitle.' - '.$cleaned, ' -');
    }

    private function mediaTypeForPath(string $path): string
    {
        $extension = strtolower(pathinfo((string) parse_url($path, PHP_URL_PATH), PATHINFO_EXTENSION));

        return in_array($extension, ['mp4', 'webm', 'mov', 'ogg'], true) ? 'video' : 'image';
    }

    private function syncMediaInput(Request $request, string $pathField, string $fileField, string $directory, ?string $currentPath = null, ?string $removeField = null): ?string
    {
        $submittedPath = trim((string) $request->input($pathField, ''));

        if ($request->hasFile($fileField)) {
            $path = PublicStorage::store($request->file($fileField), $directory);
            $this->deleteDifferentStoredAsset($currentPath, $path);

            return $path;
        }

        if ($submittedPath !== '' && $submittedPath !== $currentPath) {
            $this->deleteDifferentStoredAsset($currentPath, $submittedPath);

            return $submittedPath;
        }

        if ($request->boolean($removeField ?: 'remove_'.$pathField)) {
            $this->deleteStoredAsset($currentPath);

            return null;
        }

        if ($submittedPath !== '') {
            return $submittedPath;
        }

        return $currentPath;
    }

    private function deleteDifferentStoredAsset(?string $currentPath, ?string $nextPath): void
    {
        if ($currentPath && $currentPath !== $nextPath) {
            $this->deleteStoredAsset($currentPath);
        }
    }

    private function deleteStoredAsset(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '//')
            || str_starts_with($path, 'data:')
        ) {
            return;
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'images/') || str_starts_with($normalized, 'storage/')) {
            return;
        }

        PublicStorage::delete($normalized);
    }
}
