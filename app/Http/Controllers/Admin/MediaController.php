<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Support\Activity;
use App\Support\PublicStorage;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $query = MediaAsset::query()->with('project', 'assignment.user')->libraryOrdered();
        $supportsGallerySections = MediaAsset::supportsGallerySections();

        if ($request->filled('type')) {
            $query->where('media_type', $request->string('type')->toString());
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->integer('project_id'));
        }

        if ($supportsGallerySections && $request->filled('gallery_section')) {
            $query->where('gallery_section', $request->string('gallery_section')->toString());
        }

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('title', 'like', '%'.$search.'%')
                    ->orWhere('notes', 'like', '%'.$search.'%')
                    ->orWhereHas('project', fn ($projectQuery) => $projectQuery->where('title', 'like', '%'.$search.'%'))
                    ->orWhereHas('assignment.user', fn ($userQuery) => $userQuery->where('name', 'like', '%'.$search.'%'));
            });
        }

        return view('admin.media.index', [
            'company' => SiteData::company(),
            'media' => $query->get(),
            'projects' => Project::query()->orderBy('title')->get(),
            'assignments' => ProjectAssignment::query()->with('project', 'user', 'unit')->latest()->get(),
            'gallerySections' => MediaAsset::gallerySectionOptions(),
            'supportsGallerySections' => $supportsGallerySections,
            'uploadLimits' => $this->uploadLimits(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedMedia($request, requireAsset: true);
        $assignment = $this->resolvedAssignment($data['project_assignment_id'] ?? null);
        $projectId = $assignment?->project_id ?? ($data['project_id'] ?? null);
        $assetFile = $request->file('asset');
        $thumbnailFile = $request->file('thumbnail');
        $path = trim((string) ($data['asset_url'] ?? ''));

        if ($assetFile) {
            $path = PublicStorage::store($assetFile, $this->directoryForType($data['media_type']));
        }

        $thumbnailPath = trim((string) ($data['thumbnail_url'] ?? '')) ?: null;

        if ($thumbnailFile) {
            $thumbnailPath = PublicStorage::store($thumbnailFile, 'media/thumbnails');
        }

        if (! $thumbnailPath && $data['media_type'] === 'image') {
            $thumbnailPath = $path;
        }

        $payload = [
            'project_id' => $projectId,
            'project_assignment_id' => $assignment?->id,
            'uploaded_by' => $request->user()->id,
            'title' => $data['title'],
            'media_type' => $data['media_type'],
            'file_path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'notes' => $data['notes'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'visible_to_client' => $request->boolean('visible_to_client', true),
        ];

        if (MediaAsset::supportsGallerySections()) {
            $payload['gallery_section'] = $data['gallery_section'] ?? MediaAsset::SECTION_GENERAL;
        }

        $media = MediaAsset::query()->create($payload);

        Activity::log('media.created', 'Media asset uploaded.', $request->user(), $media);

        return back()->with('success', 'Media asset uploaded successfully.');
    }

    public function update(Request $request, MediaAsset $mediaAsset): RedirectResponse
    {
        $data = $this->validatedMedia($request, requireAsset: false);
        $assignment = $this->resolvedAssignment($data['project_assignment_id'] ?? null);
        $projectId = $assignment?->project_id ?? ($data['project_id'] ?? null);

        $currentFilePath = $mediaAsset->file_path;
        $currentThumbnailPath = $mediaAsset->thumbnail_path;
        $filePath = $currentFilePath;
        $thumbnailPath = $currentThumbnailPath;
        $assetUrl = trim((string) ($data['asset_url'] ?? ''));
        $thumbnailUrl = trim((string) ($data['thumbnail_url'] ?? ''));

        if ($request->hasFile('asset')) {
            $filePath = PublicStorage::store($request->file('asset'), $this->directoryForType($data['media_type']));

            if ($currentFilePath && $currentFilePath !== $filePath) {
                $this->deleteStoredAsset($currentFilePath);
            }

            if (($currentThumbnailPath === $currentFilePath || blank($currentThumbnailPath)) && $data['media_type'] === 'image') {
                $thumbnailPath = $filePath;
            }

            if (($currentThumbnailPath === $currentFilePath || blank($currentThumbnailPath)) && $data['media_type'] === 'video') {
                $thumbnailPath = null;
            }
        } elseif ($assetUrl !== '' && $assetUrl !== $currentFilePath) {
            if ($currentFilePath) {
                $this->deleteStoredAsset($currentFilePath);
            }

            $filePath = $assetUrl;

            if (($currentThumbnailPath === $currentFilePath || blank($currentThumbnailPath)) && $data['media_type'] === 'image') {
                $thumbnailPath = $filePath;
            }

            if (($currentThumbnailPath === $currentFilePath || blank($currentThumbnailPath)) && $data['media_type'] === 'video') {
                $thumbnailPath = null;
            }
        } elseif ($request->boolean('remove_asset')) {
            if ($currentFilePath) {
                $this->deleteStoredAsset($currentFilePath);
            }

            $filePath = null;

            if ($currentThumbnailPath === $currentFilePath) {
                $thumbnailPath = null;
            }
        }

        if ($request->hasFile('thumbnail')) {
            if ($currentThumbnailPath && $currentThumbnailPath !== $currentFilePath) {
                $this->deleteStoredAsset($currentThumbnailPath);
            }

            $thumbnailPath = PublicStorage::store($request->file('thumbnail'), 'media/thumbnails');
        } elseif ($thumbnailUrl !== '' && $thumbnailUrl !== $currentThumbnailPath) {
            if ($currentThumbnailPath && $currentThumbnailPath !== $currentFilePath) {
                $this->deleteStoredAsset($currentThumbnailPath);
            }

            $thumbnailPath = $thumbnailUrl;
        } elseif ($request->boolean('remove_thumbnail')) {
            if ($currentThumbnailPath && $currentThumbnailPath !== $currentFilePath) {
                $this->deleteStoredAsset($currentThumbnailPath);
            }

            $thumbnailPath = $data['media_type'] === 'image' ? $filePath : null;
        }

        if ($data['media_type'] === 'image' && blank($thumbnailPath) && $filePath) {
            $thumbnailPath = $filePath;
        }

        if ($data['media_type'] === 'video' && $thumbnailPath === $filePath) {
            $thumbnailPath = null;
        }

        $payload = [
            'project_id' => $projectId,
            'project_assignment_id' => $assignment?->id,
            'title' => $data['title'],
            'media_type' => $data['media_type'],
            'file_path' => $filePath,
            'thumbnail_path' => $thumbnailPath,
            'notes' => $data['notes'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'visible_to_client' => $request->boolean('visible_to_client'),
        ];

        if (MediaAsset::supportsGallerySections()) {
            $payload['gallery_section'] = $data['gallery_section'] ?? MediaAsset::SECTION_GENERAL;
        }

        $mediaAsset->update($payload);

        Activity::log('media.updated', 'Media asset updated.', $request->user(), $mediaAsset);

        return back()->with('success', 'Media asset updated successfully.');
    }

    public function destroy(Request $request, MediaAsset $mediaAsset): RedirectResponse
    {
        $filePath = $mediaAsset->file_path;
        $thumbnailPath = $mediaAsset->thumbnail_path;

        $mediaAsset->delete();

        $this->deleteStoredAsset($filePath);

        if ($thumbnailPath && $thumbnailPath !== $filePath) {
            $this->deleteStoredAsset($thumbnailPath);
        }

        Activity::log('media.deleted', 'Media asset removed.', $request->user(), $mediaAsset);

        return back()->with('success', 'Media asset deleted successfully.');
    }

    private function validatedMedia(Request $request, bool $requireAsset): array
    {
        $this->throwIfUploadFailed($request, 'asset');
        $this->throwIfUploadFailed($request, 'thumbnail');

        $rules = [
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'project_assignment_id' => ['nullable', 'integer', 'exists:project_assignments,id'],
            'title' => ['required', 'string', 'max:255'],
            'media_type' => ['required', Rule::in(['image', 'video'])],
            'notes' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'asset_url' => ['nullable', 'string', 'max:2048'],
            'thumbnail_url' => ['nullable', 'string', 'max:2048'],
            'asset' => ['nullable', 'file', 'max:204800'],
            'thumbnail' => ['nullable', 'image', 'max:204800'],
        ];

        if (MediaAsset::supportsGallerySections()) {
            $rules['gallery_section'] = ['required', Rule::in(array_keys(MediaAsset::gallerySectionOptions()))];
        }

        $data = $request->validate($rules, [
            'asset.uploaded' => $this->uploadErrorMessage(UPLOAD_ERR_INI_SIZE),
            'thumbnail.uploaded' => $this->uploadErrorMessage(UPLOAD_ERR_INI_SIZE),
            'asset.max' => 'The media file must not be larger than 200MB. Compress it or upload a smaller file.',
            'thumbnail.max' => 'The thumbnail must not be larger than 200MB.',
        ]);

        if ($requireAsset && ! $request->hasFile('asset') && blank($data['asset_url'] ?? null)) {
            throw ValidationException::withMessages([
                'asset' => 'Upload a media file or paste a CDN/direct media link before saving.',
            ]);
        }

        if (blank($data['project_id'] ?? null) && blank($data['project_assignment_id'] ?? null)) {
            throw ValidationException::withMessages([
                'project_id' => 'Select a project or a client assignment before saving media.',
            ]);
        }

        return $data;
    }

    private function resolvedAssignment(?int $assignmentId): ?ProjectAssignment
    {
        return $assignmentId ? ProjectAssignment::query()->find($assignmentId) : null;
    }

    private function directoryForType(string $type): string
    {
        return $type === 'video' ? 'media/videos' : 'media/images';
    }

    private function throwIfUploadFailed(Request $request, string $field): void
    {
        $file = $request->files->get($field);

        if (! is_object($file) || ! method_exists($file, 'isValid') || $file->isValid()) {
            return;
        }

        $error = method_exists($file, 'getError') ? $file->getError() : UPLOAD_ERR_NO_FILE;

        if ($error === UPLOAD_ERR_NO_FILE) {
            return;
        }

        throw ValidationException::withMessages([
            $field => $this->uploadErrorMessage($error),
        ]);
    }

    private function uploadErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The selected media file is larger than this running PHP server currently allows. Current upload limit: '.$this->uploadLimits()['upload_max_filesize'].'. Current POST limit: '.$this->uploadLimits()['post_max_size'].'. Restart Apache or php artisan serve after changing upload limits, then try again.',
            UPLOAD_ERR_PARTIAL => 'The media file only uploaded partially. Please try again with a stable connection.',
            UPLOAD_ERR_NO_TMP_DIR => 'PHP cannot find an upload temporary directory. Set upload_tmp_dir to C:\\xampp\\tmp and restart Apache/PHP.',
            UPLOAD_ERR_CANT_WRITE => 'PHP could not write the upload to disk. Check permissions for the temporary upload folder and storage directory.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the upload. Check the PHP/web server upload configuration.',
            default => 'The media file failed to upload. Please try again or use a smaller image/video file.',
        };
    }

    private function uploadLimits(): array
    {
        return [
            'upload_max_filesize' => ini_get('upload_max_filesize') ?: 'unknown',
            'post_max_size' => ini_get('post_max_size') ?: 'unknown',
            'memory_limit' => ini_get('memory_limit') ?: 'unknown',
            'max_file_uploads' => ini_get('max_file_uploads') ?: 'unknown',
            'upload_tmp_dir' => ini_get('upload_tmp_dir') ?: sys_get_temp_dir(),
        ];
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
