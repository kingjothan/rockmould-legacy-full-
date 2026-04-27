<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Support\Activity;
use App\Support\SiteData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        return view('admin.documents.index', [
            'company' => SiteData::company(),
            'documents' => Document::query()->with('project', 'assignment.user')->libraryOrdered()->get(),
            'projects' => Project::query()->orderBy('title')->get(),
            'assignments' => ProjectAssignment::query()->with('project', 'user')->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedDocument($request, requireFile: true);
        $assignment = $this->resolvedAssignment($data['project_assignment_id'] ?? null);
        $projectId = $assignment?->project_id ?? ($data['project_id'] ?? null);
        $path = $request->file('file')?->store('documents', 'public');

        $document = Document::query()->create([
            'project_id' => $projectId,
            'project_assignment_id' => $assignment?->id,
            'user_id' => $assignment?->user_id,
            'uploaded_by' => $request->user()->id,
            'title' => $data['title'],
            'category' => $data['category'],
            'file_path' => $path,
            'mime_type' => $request->file('file')?->getMimeType(),
            'size' => $request->file('file')?->getSize(),
            'version' => $data['version'] ?? 'v1',
            'notes' => $data['notes'] ?? null,
            'visible_to_client' => $request->boolean('visible_to_client', true),
            'is_archived' => $request->boolean('is_archived'),
        ]);

        Activity::log('document.created', 'Document uploaded in admin.', $request->user(), $document);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        $data = $this->validatedDocument($request, requireFile: false);
        $assignment = $this->resolvedAssignment($data['project_assignment_id'] ?? null);
        $projectId = $assignment?->project_id ?? ($data['project_id'] ?? null);

        $filePath = $document->file_path;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('documents', 'public');

            if ($document->file_path && $document->file_path !== $filePath) {
                $this->deleteStoredAsset($document->file_path);
            }
        }

        $document->update([
            'project_id' => $projectId,
            'project_assignment_id' => $assignment?->id,
            'user_id' => $assignment?->user_id,
            'title' => $data['title'],
            'category' => $data['category'],
            'file_path' => $filePath,
            'mime_type' => $request->file('file')?->getMimeType() ?? $document->mime_type,
            'size' => $request->file('file')?->getSize() ?? $document->size,
            'version' => $data['version'] ?? 'v1',
            'notes' => $data['notes'] ?? null,
            'visible_to_client' => $request->boolean('visible_to_client'),
            'is_archived' => $request->boolean('is_archived'),
        ]);

        Activity::log('document.updated', 'Document updated in admin.', $request->user(), $document);

        return back()->with('success', 'Document updated successfully.');
    }

    public function destroy(Request $request, Document $document): RedirectResponse
    {
        $filePath = $document->file_path;

        $document->delete();
        $this->deleteStoredAsset($filePath);

        Activity::log('document.deleted', 'Document removed from admin.', $request->user(), $document);

        return back()->with('success', 'Document deleted successfully.');
    }

    private function validatedDocument(Request $request, bool $requireFile): array
    {
        $data = $request->validate([
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'project_assignment_id' => ['nullable', 'integer', 'exists:project_assignments,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'version' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'file' => [$requireFile ? 'required' : 'nullable', 'file', 'max:8192'],
        ]);

        if (blank($data['project_id'] ?? null) && blank($data['project_assignment_id'] ?? null)) {
            throw ValidationException::withMessages([
                'project_id' => 'Select a project or a client assignment before saving the document.',
            ]);
        }

        return $data;
    }

    private function resolvedAssignment(?int $assignmentId): ?ProjectAssignment
    {
        return $assignmentId ? ProjectAssignment::query()->find($assignmentId) : null;
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
