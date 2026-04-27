<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\ProjectAssignment;
use App\Models\User;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_client_portal_shows_shared_project_documents_but_hides_foreign_assignment_documents(): void
    {
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $foreignClient = User::query()->where('email', 'chioma@example.com')->firstOrFail();
        $assignment = $client->assignments()->with('project')->firstOrFail();

        $foreignAssignment = ProjectAssignment::query()->create([
            'project_id' => $assignment->project_id,
            'user_id' => $foreignClient->id,
            'reference' => 'RM-TEST-DOC-001',
            'unit_label' => 'Unit 22A',
            'status' => 'active',
            'progress_percentage' => 35,
            'contract_amount' => 32000000,
            'amount_paid' => 14000000,
            'outstanding_balance' => 18000000,
        ]);

        Document::query()->create([
            'project_id' => $assignment->project_id,
            'project_assignment_id' => null,
            'uploaded_by' => User::query()->where('email', 'admin@rockmould.com')->value('id'),
            'title' => 'Shared Construction Programme',
            'category' => 'Programme',
            'file_path' => 'documents/shared-programme.pdf',
            'mime_type' => 'application/pdf',
            'size' => 220000,
            'version' => 'v2',
            'visible_to_client' => true,
            'is_archived' => false,
        ]);

        Document::query()->create([
            'project_id' => $assignment->project_id,
            'project_assignment_id' => $foreignAssignment->id,
            'user_id' => $foreignClient->id,
            'uploaded_by' => User::query()->where('email', 'admin@rockmould.com')->value('id'),
            'title' => 'Foreign Client Statement',
            'category' => 'Statement',
            'file_path' => 'documents/foreign-statement.pdf',
            'mime_type' => 'application/pdf',
            'size' => 180000,
            'version' => 'v1',
            'visible_to_client' => true,
            'is_archived' => false,
        ]);

        $this->actingAs($client)
            ->get('/portal/documents')
            ->assertOk()
            ->assertSee('Shared Construction Programme')
            ->assertDontSee('Foreign Client Statement');

        $this->actingAs($client)
            ->get(route('portal.projects.show', $assignment))
            ->assertOk()
            ->assertSee('Shared Construction Programme')
            ->assertDontSee('Foreign Client Statement');
    }

    public function test_admin_can_upload_update_and_delete_documents(): void
    {
        Storage::persistentFake('public');

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $assignment = ProjectAssignment::query()->with('project')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.documents.store'), [
                'project_id' => $assignment->project_id,
                'project_assignment_id' => $assignment->id,
                'title' => 'Premium Statement Pack',
                'category' => 'Statement',
                'version' => 'v1',
                'notes' => 'Initial statement upload.',
                'visible_to_client' => '1',
                'is_archived' => '0',
                'file' => UploadedFile::fake()->create('statement-pack.pdf', 240, 'application/pdf'),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $document = Document::query()->where('title', 'Premium Statement Pack')->firstOrFail();
        $originalFilePath = $document->file_path;

        $this->assertNotNull($originalFilePath);
        $this->assertTrue(Storage::disk('public')->exists($originalFilePath));

        $this->actingAs($admin)
            ->post(route('admin.documents.update', $document), [
                '_method' => 'PUT',
                'project_id' => $assignment->project_id,
                'project_assignment_id' => '',
                'title' => 'Premium Statement Pack Updated',
                'category' => 'Statement',
                'version' => 'v2',
                'notes' => 'Moved into the shared document library.',
                'visible_to_client' => '0',
                'is_archived' => '1',
                'file' => UploadedFile::fake()->create('statement-pack-updated.pdf', 280, 'application/pdf'),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $document->refresh();

        $this->assertSame('Premium Statement Pack Updated', $document->title);
        $this->assertNull($document->project_assignment_id);
        $this->assertFalse($document->visible_to_client);
        $this->assertTrue($document->is_archived);
        $this->assertSame('v2', $document->version);
        $this->assertNotSame($originalFilePath, $document->file_path);
        $this->assertTrue(Storage::disk('public')->exists($document->file_path));
        $this->assertFalse(Storage::disk('public')->exists($originalFilePath));

        $updatedFilePath = $document->file_path;

        $this->actingAs($admin)
            ->delete(route('admin.documents.destroy', $document))
            ->assertRedirect();

        $this->assertDatabaseMissing('documents', [
            'id' => $document->id,
        ]);
        $this->assertFalse(Storage::disk('public')->exists($updatedFilePath));
    }

    public function test_client_can_download_visible_documents_but_not_foreign_assignment_documents(): void
    {
        Storage::persistentFake('public');

        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $foreignClient = User::query()->where('email', 'chioma@example.com')->firstOrFail();
        $assignment = $client->assignments()->with('project')->firstOrFail();

        $foreignAssignment = ProjectAssignment::query()->create([
            'project_id' => $assignment->project_id,
            'user_id' => $foreignClient->id,
            'reference' => 'RM-TEST-DOC-DL-001',
            'unit_label' => 'Unit 41B',
            'status' => 'active',
            'progress_percentage' => 42,
            'contract_amount' => 38000000,
            'amount_paid' => 12000000,
            'outstanding_balance' => 26000000,
        ]);

        Storage::disk('public')->put('documents/shared-download-pack.pdf', 'shared portal document');
        Storage::disk('public')->put('documents/foreign-private-pack.pdf', 'foreign private document');

        $sharedDocument = Document::query()->create([
            'project_id' => $assignment->project_id,
            'project_assignment_id' => null,
            'uploaded_by' => User::query()->where('email', 'admin@rockmould.com')->value('id'),
            'title' => 'Shared Download Pack',
            'category' => 'Brochure',
            'file_path' => 'documents/shared-download-pack.pdf',
            'mime_type' => 'application/pdf',
            'size' => 1024,
            'version' => 'v1',
            'visible_to_client' => true,
            'is_archived' => false,
        ]);

        $foreignDocument = Document::query()->create([
            'project_id' => $assignment->project_id,
            'project_assignment_id' => $foreignAssignment->id,
            'user_id' => $foreignClient->id,
            'uploaded_by' => User::query()->where('email', 'admin@rockmould.com')->value('id'),
            'title' => 'Foreign Private Pack',
            'category' => 'Agreement',
            'file_path' => 'documents/foreign-private-pack.pdf',
            'mime_type' => 'application/pdf',
            'size' => 512,
            'version' => 'v1',
            'visible_to_client' => true,
            'is_archived' => false,
        ]);

        $response = $this->actingAs($client)->get(route('portal.documents.download', $sharedDocument));

        $response->assertOk();
        $this->assertStringContainsString('attachment;', (string) $response->headers->get('content-disposition'));

        $this->actingAs($client)
            ->get(route('portal.documents.download', $foreignDocument))
            ->assertNotFound();
    }
}
