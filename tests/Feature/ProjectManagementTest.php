<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\MediaAsset;
use App\Models\Project;
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
}
