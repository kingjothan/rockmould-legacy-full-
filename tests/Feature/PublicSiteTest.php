<?php

namespace Tests\Feature;

use App\Models\CareerOpening;
use App\Models\Post;
use App\Models\Project;
use App\Support\AssetPath;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_public_pages_render_successfully(): void
    {
        $career = CareerOpening::query()->where('slug', 'project-coordinator')->firstOrFail();
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();

        $routes = [
            '/',
            '/about',
            '/services',
            '/projects',
            '/portfolio',
            '/blog',
            '/faq',
            '/careers',
            '/contact',
            '/consultation',
            '/partnerships',
            '/testimonials',
            '/gallery',
            '/privacy',
            '/terms',
            '/coming-soon',
            '/maintenance',
            route('projects.show', $project),
            route('blog.show', 'future-luxury'),
            route('careers.show', $career),
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertOk();
        }
    }

    public function test_public_forms_persist_records(): void
    {
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();
        $career = CareerOpening::query()->where('slug', 'project-coordinator')->firstOrFail();

        $this->from('/contact')->post('/contact', [
            'full_name' => 'Test Visitor',
            'email' => 'visitor@example.com',
            'phone' => '+2348001234567',
            'department' => 'Sales',
            'message' => 'I want to discuss a premium project.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('contact_submissions', [
            'email' => 'visitor@example.com',
            'department' => 'Sales',
        ]);

        $this->from('/blog')->post('/newsletter', [
            'email' => 'newsletter-test@example.com',
        ])->assertRedirect('/blog');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'newsletter-test@example.com',
        ]);

        $this->from('/partnerships')->post('/partnerships', [
            'full_name' => 'Investor Lead',
            'company_name' => 'Atlas Capital',
            'email' => 'investor@example.com',
            'phone' => '+2348009876543',
            'interest_type' => 'Investment',
            'message' => 'We would like to review co-investment opportunities.',
        ])->assertRedirect('/partnerships');

        $this->assertDatabaseHas('partnership_inquiries', [
            'email' => 'investor@example.com',
            'company_name' => 'Atlas Capital',
        ]);

        $this->from(route('projects.show', $project))->post(route('forms.project-inquiry', $project), [
            'full_name' => 'Project Buyer',
            'email' => 'buyer@example.com',
            'phone' => '+2348011111111',
            'message' => 'Please send me pricing and availability.',
        ])->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('contact_submissions', [
            'email' => 'buyer@example.com',
            'department' => 'Project Inquiry: Azure Towers',
        ]);

        $this->from(route('careers.show', $career))->post(route('forms.career', $career), [
            'full_name' => 'Applicant Name',
            'email' => 'applicant@example.com',
            'phone' => '+2348022222222',
            'message' => 'I would like to be considered for this role.',
        ])->assertRedirect(route('careers.show', $career));

        $this->assertDatabaseHas('contact_submissions', [
            'email' => 'applicant@example.com',
            'department' => 'Career Application: Project Coordinator',
        ]);
    }

    public function test_unpublished_or_future_public_content_is_not_accessible(): void
    {
        $sourceProject = Project::query()->where('slug', 'azure-towers')->firstOrFail();
        $sourcePost = Post::query()->where('slug', 'future-luxury')->firstOrFail();
        $sourceCareer = CareerOpening::query()->where('slug', 'project-coordinator')->firstOrFail();

        $hiddenProject = $sourceProject->replicate();
        $hiddenProject->fill([
            'slug' => 'hidden-project',
            'title' => 'Hidden Project',
            'is_published' => false,
        ])->save();

        $scheduledPost = $sourcePost->replicate();
        $scheduledPost->fill([
            'slug' => 'scheduled-post',
            'title' => 'Scheduled Post',
            'is_published' => true,
            'published_at' => now()->addWeek(),
        ])->save();

        $closedCareer = $sourceCareer->replicate();
        $closedCareer->fill([
            'slug' => 'closed-career',
            'title' => 'Closed Career',
            'is_open' => false,
        ])->save();

        $this->get(route('projects.show', $hiddenProject))->assertNotFound();
        $this->get(route('blog.show', $scheduledPost))->assertNotFound();
        $this->get(route('careers.show', $closedCareer))->assertNotFound();

        $this->post(route('forms.project-inquiry', $hiddenProject), [
            'full_name' => 'Blocked Buyer',
            'email' => 'blocked-buyer@example.com',
            'message' => 'This should not be accepted.',
        ])->assertNotFound();

        $this->post(route('forms.career', $closedCareer), [
            'full_name' => 'Blocked Applicant',
            'email' => 'blocked-applicant@example.com',
            'message' => 'This should not be accepted.',
        ])->assertNotFound();
    }

    public function test_uploaded_media_fallback_route_serves_public_disk_files(): void
    {
        $path = 'fallback-test/'.Str::uuid().'.txt';

        Storage::disk('public')->put($path, 'public media fallback');

        $url = AssetPath::url($path);

        $this->assertStringContainsString('/uploaded-media/', $url);
        $this->get(parse_url($url, PHP_URL_PATH))->assertOk();

        Storage::disk('public')->delete($path);
    }
}
