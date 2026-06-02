<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\MediaAsset;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_admin_can_create_a_project_with_cover_media_and_documents(): void
    {
        Storage::persistentFake('public');

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
            'title' => 'Atlas Crest Residences',
            'location' => 'Ikoyi, Lagos',
            'category' => 'Luxury Residential',
            'status' => 'ongoing',
            'overview' => 'A premium mixed-use tower with branded residences and lifestyle amenities.',
            'description' => 'Designed for investors and end users who want a landmark address with a private client experience.',
            'progress' => 12,
            'budget' => 4500000000,
            'price_from' => 180000000,
            'launch_date' => '2026-01-15',
            'completion_date' => '2027-12-20',
            'features_text' => "Sky lounge\nWellness floor\nPrivate concierge",
            'gallery_text' => '',
            'highlights_json' => json_encode([
                ['label' => 'Residences', 'value' => '48'],
                ['label' => 'Completion', 'value' => 'Q4 2027'],
            ], JSON_THROW_ON_ERROR),
            'cover_image_upload' => UploadedFile::fake()->create('atlas-cover.mp4', 4096, 'video/mp4'),
            'media_uploads' => [
                UploadedFile::fake()->image('atlas-lobby.jpg', 1600, 1000),
                UploadedFile::fake()->create('atlas-site-flythrough.mp4', 4096, 'video/mp4'),
            ],
            'uploaded_media_gallery_section' => 'exterior',
            'uploaded_media_visible_to_client' => '1',
            'document_uploads' => [
                UploadedFile::fake()->create('atlas-investment-brochure.pdf', 800, 'application/pdf'),
            ],
            'document_category' => 'Brochure',
            'document_version' => 'v1',
            'uploaded_documents_visible_to_client' => '1',
            'is_featured' => '1',
            'is_portfolio' => '1',
            'is_published' => '1',
        ]);

        $project = Project::query()->where('slug', 'atlas-crest-residences')->firstOrFail();

        $response->assertRedirect(route('admin.projects.show', $project));

        $this->assertNotNull($project->cover_image);
        $this->assertStringStartsWith('projects/covers/', (string) $project->cover_image);
        $this->assertStringEndsWith('.mp4', (string) $project->cover_image);
        $this->assertTrue(Storage::disk('public')->exists($project->cover_image));
        $this->assertSame('2026-01-15', $project->launch_date?->format('Y-m-d'));
        $this->assertSame('2027-12-20', $project->completion_date?->format('Y-m-d'));

        $media = MediaAsset::query()->where('project_id', $project->id)->orderBy('id')->get();
        $documents = Document::query()->where('project_id', $project->id)->orderBy('id')->get();

        $this->assertCount(2, $media);
        $this->assertCount(1, $documents);
        $this->assertSame(['image', 'video'], $media->pluck('media_type')->all());
        $this->assertSame(['exterior', 'exterior'], $media->pluck('gallery_section')->all());
        $this->assertTrue($media->every(fn (MediaAsset $asset) => $asset->visible_to_client));
        $this->assertTrue($documents->every(fn (Document $document) => $document->visible_to_client));
        $this->assertSame('Brochure', $documents->first()->category);
        $this->assertSame('v1', $documents->first()->version);
        $this->assertTrue(Storage::disk('public')->exists($media[0]->file_path));
        $this->assertTrue(Storage::disk('public')->exists($media[1]->file_path));
        $this->assertTrue(Storage::disk('public')->exists($documents->first()->file_path));
    }

    public function test_admin_can_create_project_with_cdn_cover_and_gallery_links(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
            'title' => 'CDN Harbour Residences',
            'location' => 'Victoria Island, Lagos',
            'category' => 'Luxury Residential',
            'status' => 'upcoming',
            'overview' => 'A premium waterfront scheme with CDN-hosted project media.',
            'description' => 'Designed to validate direct media links inside project creation.',
            'cover_image' => 'https://cdn.rockmould.test/projects/harbour-cover.mp4',
            'progress' => 5,
            'budget' => 2500000000,
            'price_from' => 95000000,
            'features_text' => "Waterfront arrival\nPrivate lounge",
            'gallery_text' => '',
            'highlights_json' => json_encode([
                ['label' => 'Residences', 'value' => '36'],
            ], JSON_THROW_ON_ERROR),
            'media_urls_text' => "https://cdn.rockmould.test/projects/harbour-exterior.jpg\nhttps://cdn.rockmould.test/projects/harbour-walkthrough.mp4",
            'uploaded_media_gallery_section' => 'general',
            'uploaded_media_visible_to_client' => '1',
            'is_featured' => '1',
            'is_portfolio' => '1',
            'is_published' => '1',
        ]);

        $project = Project::query()->where('slug', 'cdn-harbour-residences')->firstOrFail();

        $response->assertRedirect(route('admin.projects.show', $project));

        $this->assertSame('https://cdn.rockmould.test/projects/harbour-cover.mp4', $project->cover_image);

        $media = MediaAsset::query()->where('project_id', $project->id)->orderBy('sort_order')->get();

        $this->assertCount(2, $media);
        $this->assertSame(['image', 'video'], $media->pluck('media_type')->all());
        $this->assertSame(['general', 'general'], $media->pluck('gallery_section')->all());
        $this->assertSame('https://cdn.rockmould.test/projects/harbour-exterior.jpg', $media[0]->file_path);
        $this->assertSame('https://cdn.rockmould.test/projects/harbour-walkthrough.mp4', $media[1]->file_path);
    }

    public function test_admin_can_update_project_start_date_from_cms(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.cms.projects.update', $project), [
            'title' => $project->title,
            'slug' => $project->slug,
            'location' => $project->location,
            'category' => $project->category,
            'status' => $project->status,
            'overview' => $project->overview,
            'description' => $project->description,
            'cover_image' => $project->cover_image,
            'launch_date' => '2026-02-10',
            'completion_date' => '2028-11-30',
            'is_featured' => '1',
            'is_portfolio' => '1',
            'is_published' => '1',
        ])->assertRedirect();

        $project->refresh();

        $this->assertSame('2026-02-10', $project->launch_date?->format('Y-m-d'));
        $this->assertSame('2028-11-30', $project->completion_date?->format('Y-m-d'));
    }

    public function test_project_timeline_uses_start_and_completion_dates(): void
    {
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();
        $project->update([
            'launch_date' => '2026-02-10',
            'completion_date' => '2028-11-30',
        ]);

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Project Start')
            ->assertSee('Feb 10, 2026')
            ->assertSee('Project Completed')
            ->assertSee('Nov 30, 2028')
            ->assertDontSee('Foundation Complete')
            ->assertDontSee('Structure Complete');
    }

    public function test_admin_can_assign_multiple_units_and_update_ownership(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $nextClient = User::query()->where('email', 'chioma@example.com')->firstOrFail();
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();

        $this->actingAs($admin)->post(route('admin.projects.assignments.store', $project), [
            'user_id' => $client->id,
            'unit_labels' => "Unit 21A\nUnit 21B",
            'status' => 'active',
            'progress_percentage' => 50,
            'contract_amount' => 30000000,
            'amount_paid' => 10000000,
        ])->assertRedirect();

        $this->assertDatabaseHas('units', [
            'project_id' => $project->id,
            'client_id' => $client->id,
            'label' => 'Unit 21A',
        ]);
        $this->assertDatabaseHas('project_assignments', [
            'project_id' => $project->id,
            'user_id' => $client->id,
            'unit_label' => 'Unit 21B',
            'outstanding_balance' => 20000000,
        ]);

        $assignment = ProjectAssignment::query()->where('unit_label', 'Unit 21A')->firstOrFail();

        $this->actingAs($admin)->post(route('admin.projects.assignments.update', [$project, $assignment]), [
            'user_id' => $nextClient->id,
            'unit_label' => 'Unit 21A-R',
            'status' => 'completed',
            'progress_percentage' => 100,
            'contract_amount' => 32000000,
            'amount_paid' => 32000000,
            'client_notes' => 'Ownership updated.',
        ])->assertRedirect();

        $assignment->refresh();
        $unit = Unit::query()->where('project_id', $project->id)->where('label', 'Unit 21A-R')->firstOrFail();

        $this->assertSame($nextClient->id, $assignment->user_id);
        $this->assertSame($unit->id, $assignment->unit_id);
        $this->assertSame('0.00', $assignment->outstanding_balance);
        $this->assertSame($nextClient->id, $unit->client_id);
    }

    public function test_a_unit_cannot_be_assigned_to_multiple_clients(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $nextClient = User::query()->where('email', 'chioma@example.com')->firstOrFail();
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();

        $this->actingAs($admin)->post(route('admin.projects.assignments.store', $project), [
            'user_id' => $client->id,
            'unit_label' => 'Single Owner Unit',
            'status' => 'active',
            'progress_percentage' => 50,
            'contract_amount' => 30000000,
            'amount_paid' => 10000000,
        ])->assertRedirect();

        $this->actingAs($admin)->from(route('admin.projects.show', $project))->post(route('admin.projects.assignments.store', $project), [
            'user_id' => $nextClient->id,
            'unit_label' => 'Single Owner Unit',
            'status' => 'active',
            'progress_percentage' => 25,
            'contract_amount' => 30000000,
            'amount_paid' => 5000000,
        ])
            ->assertRedirect(route('admin.projects.show', $project))
            ->assertSessionHasErrors('unit_labels');

        $this->assertSame(1, ProjectAssignment::query()->where('project_id', $project->id)->where('unit_label', 'Single Owner Unit')->count());
    }

    public function test_admin_can_delete_project_and_related_units(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();

        $this->actingAs($admin)->post(route('admin.projects.assignments.store', $project), [
            'user_id' => $client->id,
            'unit_label' => 'Delete Test Unit',
            'status' => 'active',
            'progress_percentage' => 25,
            'contract_amount' => 5000000,
            'amount_paid' => 1000000,
        ])->assertRedirect();

        $this->assertDatabaseHas('project_assignments', ['project_id' => $project->id]);
        $this->assertDatabaseHas('units', ['project_id' => $project->id]);

        $this->actingAs($admin)->delete(route('admin.projects.destroy', $project), [
            'confirm_title' => $project->title,
        ])->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('project_assignments', ['project_id' => $project->id]);
        $this->assertDatabaseMissing('units', ['project_id' => $project->id]);
    }
}
