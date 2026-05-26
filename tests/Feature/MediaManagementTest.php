<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\ProjectAssignment;
use App\Models\User;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaManagementTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_client_portal_only_shows_media_for_their_project_and_assignment(): void
    {
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $foreignClient = User::query()->where('email', 'chioma@example.com')->firstOrFail();
        $assignment = $client->assignments()->with('project')->firstOrFail();

        $foreignAssignment = ProjectAssignment::query()->create([
            'project_id' => $assignment->project_id,
            'user_id' => $foreignClient->id,
            'reference' => 'RM-TEST-ALT-001',
            'unit_label' => 'Unit 18C',
            'status' => 'active',
            'progress_percentage' => 20,
            'contract_amount' => 28000000,
            'amount_paid' => 5000000,
            'outstanding_balance' => 23000000,
        ]);

        MediaAsset::query()->create([
            'project_id' => $assignment->project_id,
            'project_assignment_id' => null,
            'uploaded_by' => User::query()->where('email', 'projects@rockmould.com')->value('id'),
            'title' => 'Shared Podium Progress',
            'media_type' => 'image',
            'gallery_section' => 'exterior',
            'file_path' => 'images/project-4.jpg',
            'thumbnail_path' => 'images/project-4.jpg',
            'visible_to_client' => true,
            'sort_order' => 5,
        ]);

        MediaAsset::query()->create([
            'project_id' => $assignment->project_id,
            'project_assignment_id' => $foreignAssignment->id,
            'uploaded_by' => User::query()->where('email', 'projects@rockmould.com')->value('id'),
            'title' => 'Foreign Client Progress Pack',
            'media_type' => 'image',
            'gallery_section' => 'interior',
            'file_path' => 'images/project-3.jpg',
            'thumbnail_path' => 'images/project-3.jpg',
            'visible_to_client' => true,
            'sort_order' => 6,
        ]);

        $this->actingAs($client)
            ->get('/portal/media')
            ->assertOk()
            ->assertSee('Shared Podium Progress')
            ->assertDontSee('Foreign Client Progress Pack');

        $this->actingAs($client)
            ->get(route('portal.projects.show', $assignment))
            ->assertOk()
            ->assertSee('Project Media')
            ->assertSee('Shared Podium Progress')
            ->assertDontSee('Foreign Client Progress Pack');
    }

    public function test_admin_can_upload_update_and_delete_media_assets(): void
    {
        Storage::persistentFake('public');

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $assignment = ProjectAssignment::query()->with('project')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.media.store'), [
                'project_id' => $assignment->project_id,
                'project_assignment_id' => $assignment->id,
                'title' => 'Lobby Progress Photos',
                'media_type' => 'image',
                'gallery_section' => 'interior',
                'notes' => 'Initial upload for the client gallery.',
                'sort_order' => 2,
                'visible_to_client' => '1',
                'is_featured' => '0',
                'asset' => UploadedFile::fake()->image('lobby-progress.jpg', 1600, 1000),
            ])
            ->assertRedirect();

        $asset = MediaAsset::query()->where('title', 'Lobby Progress Photos')->firstOrFail();
        $originalFilePath = $asset->file_path;

        $this->assertNotNull($originalFilePath);
        $this->assertTrue(Storage::disk('public')->exists($originalFilePath));

        $this->actingAs($admin)
            ->post(route('admin.media.update', $asset), [
                '_method' => 'PUT',
                'project_id' => $assignment->project_id,
                'project_assignment_id' => '',
                'title' => 'Lobby Progress Photos Updated',
                'media_type' => 'image',
                'gallery_section' => 'exterior',
                'notes' => 'Moved into the shared project gallery.',
                'sort_order' => 1,
                'visible_to_client' => '0',
                'is_featured' => '1',
                'asset' => UploadedFile::fake()->image('lobby-progress-updated.jpg', 1600, 1000),
                'thumbnail' => UploadedFile::fake()->image('lobby-progress-thumb.jpg', 1200, 800),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $asset->refresh();

        $this->assertSame('Lobby Progress Photos Updated', $asset->title);
        $this->assertNull($asset->project_assignment_id);
        $this->assertSame('exterior', $asset->gallery_section);
        $this->assertFalse($asset->visible_to_client);
        $this->assertTrue($asset->is_featured);
        $this->assertSame(1, $asset->sort_order);
        $this->assertNotSame($originalFilePath, $asset->file_path);
        $this->assertTrue(Storage::disk('public')->exists($asset->file_path));
        $this->assertTrue(Storage::disk('public')->exists($asset->thumbnail_path));
        $this->assertFalse(Storage::disk('public')->exists($originalFilePath));

        $updatedFilePath = $asset->file_path;
        $updatedThumbnailPath = $asset->thumbnail_path;

        $this->actingAs($admin)
            ->delete(route('admin.media.destroy', $asset))
            ->assertRedirect();

        $this->assertDatabaseMissing('media_assets', [
            'id' => $asset->id,
        ]);
        $this->assertFalse(Storage::disk('public')->exists($updatedFilePath));
        $this->assertFalse(Storage::disk('public')->exists($updatedThumbnailPath));
    }

    public function test_admin_can_create_and_replace_media_assets_with_cdn_links(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $assignment = ProjectAssignment::query()->with('project')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.media.store'), [
                'project_id' => $assignment->project_id,
                'project_assignment_id' => '',
                'title' => 'CDN Exterior Flythrough',
                'media_type' => 'video',
                'gallery_section' => 'exterior',
                'notes' => 'Hosted on a CDN for faster loading.',
                'sort_order' => 3,
                'visible_to_client' => '1',
                'is_featured' => '1',
                'asset_url' => 'https://cdn.rockmould.test/projects/exterior-flythrough.mp4',
                'thumbnail_url' => 'https://cdn.rockmould.test/projects/exterior-flythrough.jpg',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $asset = MediaAsset::query()->where('title', 'CDN Exterior Flythrough')->firstOrFail();

        $this->assertSame('https://cdn.rockmould.test/projects/exterior-flythrough.mp4', $asset->file_path);
        $this->assertSame('https://cdn.rockmould.test/projects/exterior-flythrough.jpg', $asset->thumbnail_path);
        $this->assertSame('video', $asset->media_type);
        $this->assertTrue($asset->visible_to_client);

        $this->actingAs($admin)
            ->put(route('admin.media.update', $asset), [
                'project_id' => $assignment->project_id,
                'project_assignment_id' => '',
                'title' => 'CDN Interior Gallery Still',
                'media_type' => 'image',
                'gallery_section' => 'interior',
                'notes' => 'Switched from video to image CDN asset.',
                'sort_order' => 4,
                'visible_to_client' => '1',
                'is_featured' => '0',
                'asset_url' => 'https://cdn.rockmould.test/projects/interior-gallery.jpg',
                'thumbnail_url' => '',
                'remove_thumbnail' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $asset->refresh();

        $this->assertSame('CDN Interior Gallery Still', $asset->title);
        $this->assertSame('image', $asset->media_type);
        $this->assertSame('interior', $asset->gallery_section);
        $this->assertSame('https://cdn.rockmould.test/projects/interior-gallery.jpg', $asset->file_path);
        $this->assertSame('https://cdn.rockmould.test/projects/interior-gallery.jpg', $asset->thumbnail_path);
    }

    public function test_public_gallery_hides_media_from_unpublished_projects(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $publishedAssignment = ProjectAssignment::query()->with('project')->firstOrFail();
        $unpublishedProject = $publishedAssignment->project->replicate(['title', 'slug']);
        $unpublishedProject->title = 'Private Preview Towers';
        $unpublishedProject->slug = 'private-preview-towers';
        $unpublishedProject->is_published = false;
        $unpublishedProject->is_featured = false;
        $unpublishedProject->save();

        MediaAsset::query()->create([
            'project_id' => $publishedAssignment->project_id,
            'project_assignment_id' => null,
            'uploaded_by' => $admin->id,
            'title' => 'Published Skyline Gallery',
            'media_type' => 'image',
            'gallery_section' => 'exterior',
            'file_path' => 'images/project-1.jpg',
            'thumbnail_path' => 'images/project-1.jpg',
            'visible_to_client' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        MediaAsset::query()->create([
            'project_id' => $unpublishedProject->id,
            'project_assignment_id' => null,
            'uploaded_by' => $admin->id,
            'title' => 'Unpublished Preview Gallery',
            'media_type' => 'image',
            'gallery_section' => 'interior',
            'file_path' => 'images/project-2.jpg',
            'thumbnail_path' => 'images/project-2.jpg',
            'visible_to_client' => true,
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        $this->get('/gallery')
            ->assertOk()
            ->assertSee('Published Skyline Gallery')
            ->assertDontSee('Unpublished Preview Gallery');
    }

    public function test_client_can_download_visible_media_but_not_foreign_assignment_media(): void
    {
        Storage::persistentFake('public');

        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $foreignClient = User::query()->where('email', 'chioma@example.com')->firstOrFail();
        $assignment = $client->assignments()->with('project')->firstOrFail();

        $foreignAssignment = ProjectAssignment::query()->create([
            'project_id' => $assignment->project_id,
            'user_id' => $foreignClient->id,
            'reference' => 'RM-TEST-MEDIA-DL-001',
            'unit_label' => 'Unit 51D',
            'status' => 'active',
            'progress_percentage' => 33,
            'contract_amount' => 41000000,
            'amount_paid' => 9000000,
            'outstanding_balance' => 32000000,
        ]);

        Storage::disk('public')->put('media/images/shared-media-download.jpg', 'shared image');
        Storage::disk('public')->put('media/videos/foreign-private-video.mp4', 'foreign video');

        $sharedAsset = MediaAsset::query()->create([
            'project_id' => $assignment->project_id,
            'project_assignment_id' => null,
            'uploaded_by' => User::query()->where('email', 'projects@rockmould.com')->value('id'),
            'title' => 'Shared Media Download',
            'media_type' => 'image',
            'gallery_section' => 'exterior',
            'file_path' => 'media/images/shared-media-download.jpg',
            'thumbnail_path' => 'media/images/shared-media-download.jpg',
            'visible_to_client' => true,
            'sort_order' => 1,
        ]);

        $foreignAsset = MediaAsset::query()->create([
            'project_id' => $assignment->project_id,
            'project_assignment_id' => $foreignAssignment->id,
            'uploaded_by' => User::query()->where('email', 'projects@rockmould.com')->value('id'),
            'title' => 'Foreign Private Video',
            'media_type' => 'video',
            'gallery_section' => 'interior',
            'file_path' => 'media/videos/foreign-private-video.mp4',
            'thumbnail_path' => null,
            'visible_to_client' => true,
            'sort_order' => 2,
        ]);

        $response = $this->actingAs($client)->get(route('portal.media.download', $sharedAsset));

        $response->assertOk();
        $this->assertStringContainsString('attachment;', (string) $response->headers->get('content-disposition'));

        $this->actingAs($client)
            ->get(route('portal.media.download', $foreignAsset))
            ->assertNotFound();
    }

    public function test_public_gallery_filters_by_section_and_project_page_groups_exterior_and_interior_media(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $project = ProjectAssignment::query()->with('project')->firstOrFail()->project;

        MediaAsset::query()->create([
            'project_id' => $project->id,
            'project_assignment_id' => null,
            'uploaded_by' => $admin->id,
            'title' => 'Public Exterior Showcase',
            'media_type' => 'image',
            'gallery_section' => 'exterior',
            'file_path' => 'images/project-1.jpg',
            'thumbnail_path' => 'images/project-1.jpg',
            'visible_to_client' => true,
            'sort_order' => 1,
        ]);

        MediaAsset::query()->create([
            'project_id' => $project->id,
            'project_assignment_id' => null,
            'uploaded_by' => $admin->id,
            'title' => 'Public Interior Showcase',
            'media_type' => 'image',
            'gallery_section' => 'interior',
            'file_path' => 'images/project-2.jpg',
            'thumbnail_path' => 'images/project-2.jpg',
            'visible_to_client' => true,
            'sort_order' => 2,
        ]);

        MediaAsset::query()->create([
            'project_id' => $project->id,
            'project_assignment_id' => null,
            'uploaded_by' => $admin->id,
            'title' => 'Public Mixed Gallery',
            'media_type' => 'video',
            'gallery_section' => 'general',
            'file_path' => 'media/videos/public-mixed-gallery.mp4',
            'thumbnail_path' => null,
            'visible_to_client' => true,
            'sort_order' => 3,
        ]);

        $this->get('/gallery?section=exterior')
            ->assertOk()
            ->assertSee('Exterior Gallery')
            ->assertSee('Public Exterior Showcase')
            ->assertDontSee('Public Interior Showcase');

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Exterior Gallery')
            ->assertSee('Interior Gallery')
            ->assertSee('Gallery')
            ->assertSee('Public Exterior Showcase')
            ->assertSee('Public Interior Showcase')
            ->assertSee('Public Mixed Gallery')
            ->assertDontSee('General Gallery');
    }
}
