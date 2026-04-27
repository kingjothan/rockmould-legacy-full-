<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\Post;
use App\Models\CareerOpening;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TimelineEntry;
use App\Models\User;
use App\Support\SiteData;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_admin_can_update_homepage_and_about_content_with_uploaded_media(): void
    {
        Storage::persistentFake('public');

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.cms.homepage.update'), [
                '_method' => 'PUT',
                'hero_label' => 'Signature Developments',
                'hero_title' => 'Refined Places for Serious Investors',
                'hero_description' => 'A sharper public homepage message for Rockmould.',
                'hero_media_path' => 'images/project-2.jpg',
                'hero_primary_cta' => 'Browse Developments',
                'hero_secondary_cta' => 'Schedule a Meeting',
                'hero_portal_cta' => 'Client Access',
                'featured_label' => 'Live Portfolio',
                'featured_title' => 'Projects Built for Long-Term Confidence',
                'featured_description' => 'Updated featured developments copy from the CMS.',
                'featured_cta' => 'See Full Portfolio',
                'services_label' => 'What We Do',
                'services_title' => 'Structured Real Estate Delivery',
                'services_description' => 'Updated services write-up from the CMS.',
                'why_label' => 'Why Clients Choose Rockmould',
                'why_title' => 'Disciplined Delivery From First Brief',
                'why_cta' => 'Read Our Story',
                'process_label' => 'Project Journey',
                'process_title' => 'From First Meeting to Final Handover',
                'process_description' => 'Updated process intro from the CMS.',
                'testimonials_label' => 'Trusted Relationships',
                'testimonials_title' => 'Investors and Clients Speak Clearly',
                'insights_label' => 'Market Intelligence',
                'insights_title' => 'Latest Thinking From Rockmould',
                'insights_description' => 'Updated insights intro from the CMS.',
                'portal_title' => 'Client Access Portal',
                'portal_description' => 'A refined place to track documents, balances, and project updates.',
                'portal_cta' => 'Open Portal',
                'final_cta_title' => 'Start the Next Signature Project',
                'final_cta_description' => 'Updated closing homepage call-to-action from the CMS.',
                'final_cta_primary' => 'Book a Strategy Session',
                'final_cta_secondary' => 'Review Active Projects',
                'why_choose_text' => "Delivery certainty\nPremium reporting\nDisciplined execution",
                'stats_text' => "Projects Delivered|180+\nYears of Excellence|22+",
                'process_text' => "01|Brief|Align the project vision\n02|Deliver|Execute with precision",
                'hero_media' => UploadedFile::fake()->image('homepage-hero.jpg', 1800, 1200),
                'feature_image' => UploadedFile::fake()->create('homepage-feature.mp4', 4096, 'video/mp4'),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $homepage = Setting::query()->where('key', 'homepage')->firstOrFail()->value;

        $this->assertSame('Refined Places for Serious Investors', $homepage['hero_title']);
        $this->assertStringStartsWith('cms/homepage/', $homepage['hero_media_path']);
        $this->assertStringStartsWith('cms/homepage/', $homepage['feature_image']);
        $this->assertSame('Projects Built for Long-Term Confidence', $homepage['featured_section']['title']);
        $this->assertSame('Disciplined Delivery From First Brief', $homepage['why_section']['title']);
        $this->assertSame('Client Access Portal', $homepage['portal_section']['title']);
        $this->assertSame('Start the Next Signature Project', $homepage['final_cta']['title']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Refined Places for Serious Investors')
            ->assertSee('Projects Built for Long-Term Confidence')
            ->assertSee('Delivery certainty')
            ->assertSee('Client Access Portal')
            ->assertSee('Start the Next Signature Project');

        $this->actingAs($admin)
            ->post(route('admin.cms.about.update'), [
                '_method' => 'PUT',
                'hero_subtitle' => 'Our Story',
                'hero_title' => 'Built on Discipline and Legacy',
                'hero_description' => 'Updated about hero copy from the CMS.',
                'story_label' => 'Company Story',
                'story_title' => 'A More Detailed Rockmould Story',
                'story' => "First paragraph\nSecond paragraph",
                'vision_title' => 'Future Vision',
                'vision' => 'Updated vision statement.',
                'mission_title' => 'Delivery Mission',
                'mission' => 'Updated mission statement.',
                'values_label' => 'Operating Values',
                'values_title' => 'Values That Guide Delivery',
                'team_label' => 'Executive Leadership',
                'team_title' => 'People Behind the Vision',
                'timeline_label' => 'Company Milestones',
                'timeline_title' => 'Growth and Excellence',
                'cta_title' => 'Build With Rockmould',
                'cta_description' => 'Updated about CTA from the CMS.',
                'cta_primary' => 'Book Consultation',
                'cta_secondary' => 'View Developments',
                'values_text' => "Integrity|Clear and transparent delivery\nStewardship|Long-term value creation",
                'metrics_text' => "Projects|180+\nClients|540+",
                'hero_media' => UploadedFile::fake()->image('about-hero.jpg', 1800, 1200),
                'story_image' => UploadedFile::fake()->create('about-story.mp4', 4096, 'video/mp4'),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $about = Setting::query()->where('key', 'about')->firstOrFail()->value;

        $this->assertSame('Built on Discipline and Legacy', $about['hero_title']);
        $this->assertSame('People Behind the Vision', $about['team_title']);
        $this->assertSame('Growth and Excellence', $about['timeline_title']);
        $this->assertStringStartsWith('cms/about/', $about['hero_media_path']);
        $this->assertStringStartsWith('cms/about/', $about['story_image']);

        $this->get('/about')
            ->assertOk()
            ->assertSee('Built on Discipline and Legacy')
            ->assertSee('A More Detailed Rockmould Story')
            ->assertSee('Clear and transparent delivery')
            ->assertSee('People Behind the Vision')
            ->assertSee('Growth and Excellence')
            ->assertSee('Build With Rockmould');
    }

    public function test_admin_can_manage_about_team_members_and_timeline_milestones(): void
    {
        Storage::persistentFake('public');

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.cms.about.team.store'), [
                'name' => 'Amara Okonkwo',
                'role' => 'Director of Client Experience',
                'bio' => 'Leads client communication, handover quality, and portfolio experience.',
                'image_upload' => UploadedFile::fake()->create('amara-profile.mp4', 4096, 'video/mp4'),
                'sort_order' => 1,
                'is_visible' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $member = TeamMember::query()->where('name', 'Amara Okonkwo')->firstOrFail();

        $this->assertStringStartsWith('cms/about/team/', $member->image_path);

        $this->actingAs($admin)
            ->put(route('admin.cms.about.team.update', $member), [
                'name' => 'Amara Okonkwo-Ibe',
                'role' => 'Director of Client Experience',
                'bio' => 'Updated leadership profile for the About page.',
                'image_path' => $member->image_path,
                'sort_order' => 2,
                'is_visible' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $member->refresh();

        $this->assertSame('Amara Okonkwo-Ibe', $member->name);
        $this->assertSame(2, $member->sort_order);

        $this->actingAs($admin)
            ->post(route('admin.cms.about.timeline.store'), [
                'year' => '2027',
                'title' => 'Regional Expansion',
                'description' => 'Rockmould expands its premium delivery model into new regional markets.',
                'sort_order' => 7,
                'is_visible' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $entry = TimelineEntry::query()->where('title', 'Regional Expansion')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.cms.about.timeline.update', $entry), [
                'year' => '2028',
                'title' => 'Regional Expansion Programme',
                'description' => 'Updated milestone copy controlled from the About CMS.',
                'sort_order' => 8,
                'is_visible' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->get('/about')
            ->assertOk()
            ->assertSee('Amara Okonkwo-Ibe')
            ->assertSee('Regional Expansion Programme');
    }

    public function test_admin_can_update_public_records_and_public_gallery_stays_scoped(): void
    {
        Storage::persistentFake('public');

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $service = Service::query()->where('slug', 'real-estate-development')->firstOrFail();
        $post = Post::query()->where('slug', 'future-luxury')->firstOrFail();
        $testimonial = Testimonial::query()->firstOrFail();
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();
        $assignment = $project->assignments()->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.cms.services.update', $service), [
                '_method' => 'PUT',
                'title' => 'Luxury Development Advisory',
                'slug' => 'luxury-development-advisory',
                'tagline' => 'Updated service tagline',
                'summary' => 'Updated service summary.',
                'description' => 'Updated service description.',
                'image_path' => '',
                'image_upload' => UploadedFile::fake()->create('service-cover.mp4', 4096, 'video/mp4'),
                'benefits_text' => "Structured delivery\nPremium service",
                'process_steps_text' => "Briefing\nDelivery",
                'related_projects_text' => "azure-towers\ncommerce-tower",
                'sort_order' => 1,
                'is_featured' => '1',
                'is_published' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->post(route('admin.cms.blog.update', $post), [
                '_method' => 'PUT',
                'title' => 'Luxury Market Signals for 2026',
                'slug' => 'luxury-market-signals-for-2026',
                'category' => 'Market Insight',
                'author_name' => 'Rockmould Editorial',
                'read_time' => '7 min',
                'excerpt' => 'Updated blog excerpt.',
                'intro' => 'Updated intro.',
                'quote' => 'Updated quote.',
                'cover_image' => '',
                'cover_image_upload' => UploadedFile::fake()->create('post-cover.mp4', 4096, 'video/mp4'),
                'content_json' => json_encode([
                    ['heading' => 'Headline', 'paragraphs' => ['Updated paragraph.']],
                ]),
                'published_at' => now()->format('Y-m-d H:i:s'),
                'featured' => '1',
                'is_published' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->post(route('admin.cms.testimonials.update', $testimonial), [
                '_method' => 'PUT',
                'project_id' => $project->id,
                'client_name' => 'Updated Investor Client',
                'role' => 'Investor',
                'quote' => 'Updated testimonial quote.',
                'rating' => 5,
                'sort_order' => 1,
                'image_path' => '',
                'image_upload' => UploadedFile::fake()->create('testimonial.mp4', 4096, 'video/mp4'),
                'is_visible' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->post(route('admin.cms.projects.update', $project), [
                '_method' => 'PUT',
                'title' => 'Azure Towers Residences',
                'slug' => 'azure-towers',
                'location' => 'Victoria Island, Lagos',
                'category' => 'Residential',
                'status' => 'ongoing',
                'overview' => 'Updated project overview.',
                'description' => 'Updated project description.',
                'cover_image' => '',
                'cover_image_upload' => UploadedFile::fake()->create('project-cover.mp4', 4096, 'video/mp4'),
                'is_featured' => '1',
                'is_published' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        MediaAsset::query()->create([
            'project_id' => $project->id,
            'project_assignment_id' => null,
            'uploaded_by' => $admin->id,
            'title' => 'Shared Skyline Drone',
            'media_type' => 'image',
            'file_path' => 'images/project-1.jpg',
            'thumbnail_path' => 'images/project-1.jpg',
            'visible_to_client' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        MediaAsset::query()->create([
            'project_id' => $project->id,
            'project_assignment_id' => $assignment->id,
            'uploaded_by' => $admin->id,
            'title' => 'Private Unit Walkthrough',
            'media_type' => 'video',
            'file_path' => 'images/project-2.jpg',
            'thumbnail_path' => 'images/project-2.jpg',
            'visible_to_client' => true,
            'is_featured' => false,
            'sort_order' => 2,
        ]);

        $this->get('/services')->assertOk()->assertSee('Luxury Development Advisory');
        $this->get('/blog')->assertOk()->assertSee('Luxury Market Signals for 2026');
        $this->get('/testimonials')->assertOk()->assertSee('Updated Investor Client');
        $this->get(route('projects.show', $project))->assertOk()->assertSee('Azure Towers Residences')->assertSee('Project Media');
        $this->get('/gallery')->assertOk()->assertSee('Shared Skyline Drone')->assertDontSee('Private Unit Walkthrough');
    }

    public function test_admin_can_manage_portfolio_page_content_and_project_visibility(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.cms.portfolio.update'), [
                'hero_subtitle' => 'Signature Work',
                'hero_title' => 'Portfolio That Builds Confidence',
                'hero_description' => 'Updated portfolio copy from the CMS.',
                'metrics_text' => "Developments|24\nDelivered Value|NGN 82B",
                'client_success_label' => 'Client Trust',
                'client_success_title' => 'Why Clients Keep Returning',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->actingAs($admin)
            ->put(route('admin.cms.projects.update', $project), [
                'title' => $project->title,
                'slug' => $project->slug,
                'location' => $project->location,
                'category' => $project->category,
                'status' => $project->status,
                'overview' => $project->overview,
                'description' => $project->description,
                'cover_image' => $project->cover_image,
                'completion_date' => optional($project->completion_date)->format('Y-m-d'),
                'is_featured' => $project->is_featured ? '1' : '0',
                'is_published' => '1',
                'is_portfolio' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $portfolio = Setting::query()->where('key', 'portfolio')->firstOrFail()->value;

        $this->assertSame('Portfolio That Builds Confidence', $portfolio['hero_title']);
        $this->assertSame('Client Trust', $portfolio['client_success_label']);
        $this->assertSame('Why Clients Keep Returning', $portfolio['client_success_title']);

        $project->refresh();
        $this->assertTrue($project->is_portfolio);

        $this->get('/portfolio')
            ->assertOk()
            ->assertSee('Portfolio That Builds Confidence')
            ->assertSee('Updated portfolio copy from the CMS.')
            ->assertSee('Why Clients Keep Returning')
            ->assertSee($project->title);
    }

    public function test_admin_can_update_public_pages_and_portal_content(): void
    {
        Storage::persistentFake('public');

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();

        $pagesPayload = $this->publicPagesPayload(SiteData::publicPages());
        $pagesPayload['services_hero_title'] = 'Service Excellence Across Every Stage';
        $pagesPayload['contact_form_title'] = 'Talk to Rockmould';
        $pagesPayload['gallery_empty_title'] = 'No Visual Updates Yet';

        $this->actingAs($admin)
            ->put(route('admin.cms.pages.update'), $pagesPayload)
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $publicPages = Setting::query()->where('key', 'public_pages')->firstOrFail()->value;

        $this->assertSame('Service Excellence Across Every Stage', $publicPages['services']['hero_title']);
        $this->assertSame('Talk to Rockmould', $publicPages['contact']['form_title']);
        $this->assertSame('No Visual Updates Yet', $publicPages['gallery']['empty_title']);

        $this->get('/services')->assertOk()->assertSee('Service Excellence Across Every Stage');
        $this->get('/contact')->assertOk()->assertSee('Talk to Rockmould');

        $portalPayload = $this->portalContentPayload(SiteData::portalContent());
        $portalPayload['login_welcome_title'] = 'Secure Client Access';
        $portalPayload['support_title'] = 'Portal Support Centre';
        $portalPayload['documents_title'] = 'Project Document Library';

        $this->actingAs($admin)
            ->put(route('admin.cms.portal.update'), $portalPayload)
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $portalContent = Setting::query()->where('key', 'portal_content')->firstOrFail()->value;

        $this->assertSame('Secure Client Access', $portalContent['login']['welcome_title']);
        $this->assertSame('Portal Support Centre', $portalContent['support']['title']);
        $this->assertSame('Project Document Library', $portalContent['documents']['title']);

        $this->actingAs($admin)
            ->post(route('admin.logout'))
            ->assertRedirect();

        $this->get('/portal')->assertOk()->assertSee('Secure Client Access');
        $this->actingAs($client)->get('/portal/support')->assertOk()->assertSee('Portal Support Centre');
        $this->actingAs($client)->get('/portal/documents')->assertOk()->assertSee('Project Document Library');
    }

    public function test_admin_can_store_career_without_optional_slug(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.cms.careers.store'), [
                'title' => 'HR',
                'department' => 'Admin',
                'location' => 'Lagos',
                'employment_type' => 'Full time',
                'summary' => 'Support reporting, scheduling, and coordination across active construction and development projects.',
                'description' => 'You will work with project directors, consultants, and contractors to maintain reporting discipline and delivery momentum.',
                'requirements_text' => "3+ years in project coordination\nStrong reporting skills\nConstruction experience",
                'benefits_text' => "Health coverage\nProfessional development\nPerformance bonus",
                'is_open' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('career_openings', [
            'title' => 'HR',
            'slug' => 'hr',
            'department' => 'Admin',
            'location' => 'Lagos',
            'employment_type' => 'Full time',
            'is_open' => true,
        ]);
    }

    public function test_admin_can_update_and_delete_career_roles(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $career = CareerOpening::query()->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.cms.careers.update', $career), [
                '_method' => 'PUT',
                'title' => 'Senior Project Coordinator',
                'slug' => '',
                'department' => 'Operations',
                'location' => 'Abuja',
                'employment_type' => 'Hybrid',
                'summary' => 'Updated operations role summary.',
                'description' => 'Updated description for a more senior coordination role.',
                'requirements_text' => "Leadership\nReporting\nConstruction delivery",
                'benefits_text' => "Bonus\nGrowth\nMedical cover",
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $career->refresh();

        $this->assertSame('Senior Project Coordinator', $career->title);
        $this->assertSame('senior-project-coordinator', $career->slug);
        $this->assertSame('Operations', $career->department);
        $this->assertSame('Abuja', $career->location);
        $this->assertSame('Hybrid', $career->employment_type);
        $this->assertFalse($career->is_open);

        $this->actingAs($admin)
            ->delete(route('admin.cms.careers.destroy', $career))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('career_openings', [
            'id' => $career->id,
        ]);
    }

    private function publicPagesPayload(array $pages): array
    {
        return [
            'services_hero_subtitle' => data_get($pages, 'services.hero_subtitle'),
            'services_hero_title' => data_get($pages, 'services.hero_title'),
            'services_hero_description' => data_get($pages, 'services.hero_description'),
            'services_hero_media_path' => data_get($pages, 'services.hero_media_path'),
            'services_cta_title' => data_get($pages, 'services.cta_title'),
            'services_cta_description' => data_get($pages, 'services.cta_description'),
            'services_cta_button' => data_get($pages, 'services.cta_button'),

            'projects_hero_subtitle' => data_get($pages, 'projects.hero_subtitle'),
            'projects_hero_title' => data_get($pages, 'projects.hero_title'),
            'projects_hero_description' => data_get($pages, 'projects.hero_description'),
            'projects_hero_media_path' => data_get($pages, 'projects.hero_media_path'),
            'projects_empty_title' => data_get($pages, 'projects.empty_title'),
            'projects_empty_description' => data_get($pages, 'projects.empty_description'),
            'projects_empty_button' => data_get($pages, 'projects.empty_button'),

            'blog_hero_subtitle' => data_get($pages, 'blog.hero_subtitle'),
            'blog_hero_title' => data_get($pages, 'blog.hero_title'),
            'blog_hero_description' => data_get($pages, 'blog.hero_description'),
            'blog_hero_media_path' => data_get($pages, 'blog.hero_media_path'),
            'blog_featured_label' => data_get($pages, 'blog.featured_label'),
            'blog_empty_title' => data_get($pages, 'blog.empty_title'),
            'blog_empty_description' => data_get($pages, 'blog.empty_description'),
            'blog_subscribe_title' => data_get($pages, 'blog.subscribe_title'),
            'blog_subscribe_description' => data_get($pages, 'blog.subscribe_description'),
            'blog_subscribe_button' => data_get($pages, 'blog.subscribe_button'),

            'faq_hero_subtitle' => data_get($pages, 'faq.hero_subtitle'),
            'faq_hero_title' => data_get($pages, 'faq.hero_title'),
            'faq_hero_description' => data_get($pages, 'faq.hero_description'),
            'faq_hero_media_path' => data_get($pages, 'faq.hero_media_path'),
            'faq_empty_title' => data_get($pages, 'faq.empty_title'),
            'faq_empty_description' => data_get($pages, 'faq.empty_description'),

            'careers_hero_subtitle' => data_get($pages, 'careers.hero_subtitle'),
            'careers_hero_title' => data_get($pages, 'careers.hero_title'),
            'careers_hero_description' => data_get($pages, 'careers.hero_description'),
            'careers_hero_media_path' => data_get($pages, 'careers.hero_media_path'),
            'careers_benefits_text' => collect(data_get($pages, 'careers.benefits', []))
                ->map(fn ($benefit) => ($benefit['title'] ?? '').'|'.($benefit['description'] ?? ''))
                ->implode(PHP_EOL),
            'careers_openings_title' => data_get($pages, 'careers.openings_title'),
            'careers_openings_description' => data_get($pages, 'careers.openings_description'),
            'careers_open_application_kicker' => data_get($pages, 'careers.open_application_kicker'),
            'careers_open_application_title' => data_get($pages, 'careers.open_application_title'),
            'careers_open_application_description' => data_get($pages, 'careers.open_application_description'),
            'careers_open_application_button' => data_get($pages, 'careers.open_application_button'),
            'careers_empty_title' => data_get($pages, 'careers.empty_title'),
            'careers_empty_description' => data_get($pages, 'careers.empty_description'),

            'contact_hero_subtitle' => data_get($pages, 'contact.hero_subtitle'),
            'contact_hero_title' => data_get($pages, 'contact.hero_title'),
            'contact_hero_description' => data_get($pages, 'contact.hero_description'),
            'contact_hero_media_path' => data_get($pages, 'contact.hero_media_path'),
            'contact_info_title' => data_get($pages, 'contact.info_title'),
            'contact_quick_access_title' => data_get($pages, 'contact.quick_access_title'),
            'contact_form_title' => data_get($pages, 'contact.form_title'),

            'partnerships_hero_subtitle' => data_get($pages, 'partnerships.hero_subtitle'),
            'partnerships_hero_title' => data_get($pages, 'partnerships.hero_title'),
            'partnerships_hero_description' => data_get($pages, 'partnerships.hero_description'),
            'partnerships_hero_media_path' => data_get($pages, 'partnerships.hero_media_path'),
            'partnerships_reasons_title' => data_get($pages, 'partnerships.reasons_title'),
            'partnerships_reasons_text' => collect(data_get($pages, 'partnerships.reasons', []))->implode(PHP_EOL),
            'partnerships_metrics_text' => collect(data_get($pages, 'partnerships.metrics', []))
                ->map(fn ($metric) => ($metric['label'] ?? '').'|'.($metric['value'] ?? ''))
                ->implode(PHP_EOL),
            'partnerships_form_title' => data_get($pages, 'partnerships.form_title'),
            'partnerships_opportunities_label' => data_get($pages, 'partnerships.opportunities_label'),
            'partnerships_opportunities_title' => data_get($pages, 'partnerships.opportunities_title'),
            'partnerships_submit_button' => data_get($pages, 'partnerships.submit_button'),

            'testimonials_hero_subtitle' => data_get($pages, 'testimonials.hero_subtitle'),
            'testimonials_hero_title' => data_get($pages, 'testimonials.hero_title'),
            'testimonials_hero_description' => data_get($pages, 'testimonials.hero_description'),
            'testimonials_hero_media_path' => data_get($pages, 'testimonials.hero_media_path'),

            'gallery_hero_subtitle' => data_get($pages, 'gallery.hero_subtitle'),
            'gallery_hero_title' => data_get($pages, 'gallery.hero_title'),
            'gallery_hero_description' => data_get($pages, 'gallery.hero_description'),
            'gallery_hero_media_path' => data_get($pages, 'gallery.hero_media_path'),
            'gallery_empty_title' => data_get($pages, 'gallery.empty_title'),
            'gallery_empty_description' => data_get($pages, 'gallery.empty_description'),
        ];
    }

    private function portalContentPayload(array $portal): array
    {
        return [
            'login_media_path' => data_get($portal, 'login.media_path'),
            'login_marketing_title' => data_get($portal, 'login.marketing_title'),
            'login_marketing_highlight' => data_get($portal, 'login.marketing_highlight'),
            'login_features_text' => collect(data_get($portal, 'login.features', []))->implode(PHP_EOL),
            'login_pending_title' => data_get($portal, 'login.pending_title'),
            'login_pending_description' => data_get($portal, 'login.pending_description'),
            'login_pending_helper' => data_get($portal, 'login.pending_helper'),
            'login_unrecognized_title' => data_get($portal, 'login.unrecognized_title'),
            'login_unrecognized_description' => data_get($portal, 'login.unrecognized_description'),
            'login_unrecognized_button' => data_get($portal, 'login.unrecognized_button'),
            'login_kyc_title' => data_get($portal, 'login.kyc_title'),
            'login_kyc_description' => data_get($portal, 'login.kyc_description'),
            'login_welcome_title' => data_get($portal, 'login.welcome_title'),
            'login_welcome_description' => data_get($portal, 'login.welcome_description'),
            'login_access_button' => data_get($portal, 'login.access_button'),
            'login_request_access_text' => data_get($portal, 'login.request_access_text'),
            'login_request_access_link' => data_get($portal, 'login.request_access_link'),
            'login_support_text' => data_get($portal, 'login.support_text'),
            'login_support_link' => data_get($portal, 'login.support_link'),

            'dashboard_welcome_title' => data_get($portal, 'dashboard.welcome_title'),
            'dashboard_description' => data_get($portal, 'dashboard.description'),
            'dashboard_active_projects_label' => data_get($portal, 'dashboard.active_projects_label'),
            'dashboard_amount_paid_label' => data_get($portal, 'dashboard.amount_paid_label'),
            'dashboard_outstanding_label' => data_get($portal, 'dashboard.outstanding_label'),
            'dashboard_documents_label' => data_get($portal, 'dashboard.documents_label'),
            'dashboard_projects_title' => data_get($portal, 'dashboard.projects_title'),
            'dashboard_projects_link' => data_get($portal, 'dashboard.projects_link'),
            'dashboard_projects_empty' => data_get($portal, 'dashboard.projects_empty'),
            'dashboard_updates_title' => data_get($portal, 'dashboard.updates_title'),
            'dashboard_updates_empty' => data_get($portal, 'dashboard.updates_empty'),
            'dashboard_documents_title' => data_get($portal, 'dashboard.documents_title'),
            'dashboard_documents_empty' => data_get($portal, 'dashboard.documents_empty'),

            'projects_kicker' => data_get($portal, 'projects.kicker'),
            'projects_title' => data_get($portal, 'projects.title'),
            'projects_description' => data_get($portal, 'projects.description'),
            'projects_filter_button' => data_get($portal, 'projects.filter_button'),
            'projects_assigned_label' => data_get($portal, 'projects.assigned_label'),
            'projects_assigned_description' => data_get($portal, 'projects.assigned_description'),
            'projects_paid_label' => data_get($portal, 'projects.paid_label'),
            'projects_paid_description' => data_get($portal, 'projects.paid_description'),
            'projects_outstanding_label' => data_get($portal, 'projects.outstanding_label'),
            'projects_outstanding_description' => data_get($portal, 'projects.outstanding_description'),
            'projects_empty_kicker' => data_get($portal, 'projects.empty_kicker'),
            'projects_empty_title' => data_get($portal, 'projects.empty_title'),
            'projects_empty_description' => data_get($portal, 'projects.empty_description'),
            'projects_card_button' => data_get($portal, 'projects.card_button'),

            'documents_title' => data_get($portal, 'documents.title'),
            'documents_description' => data_get($portal, 'documents.description'),
            'documents_empty_text' => data_get($portal, 'documents.empty_text'),

            'media_kicker' => data_get($portal, 'media.kicker'),
            'media_title' => data_get($portal, 'media.title'),
            'media_description' => data_get($portal, 'media.description'),
            'media_assets_label' => data_get($portal, 'media.assets_label'),
            'media_photos_label' => data_get($portal, 'media.photos_label'),
            'media_videos_label' => data_get($portal, 'media.videos_label'),
            'media_empty_title' => data_get($portal, 'media.empty_title'),
            'media_empty_description' => data_get($portal, 'media.empty_description'),

            'balance_kicker' => data_get($portal, 'balance.kicker'),
            'balance_title' => data_get($portal, 'balance.title'),
            'balance_description' => data_get($portal, 'balance.description'),
            'balance_contract_label' => data_get($portal, 'balance.contract_label'),
            'balance_paid_label' => data_get($portal, 'balance.paid_label'),
            'balance_outstanding_label' => data_get($portal, 'balance.outstanding_label'),
            'balance_history_title' => data_get($portal, 'balance.history_title'),
            'balance_empty_title' => data_get($portal, 'balance.empty_title'),
            'balance_empty_description' => data_get($portal, 'balance.empty_description'),

            'updates_title' => data_get($portal, 'updates.title'),
            'updates_description' => data_get($portal, 'updates.description'),
            'updates_empty_title' => data_get($portal, 'updates.empty_title'),
            'updates_empty_description' => data_get($portal, 'updates.empty_description'),

            'profile_title' => data_get($portal, 'profile.title'),
            'profile_support_title' => data_get($portal, 'profile.support_title'),
            'profile_support_empty' => data_get($portal, 'profile.support_empty'),
            'profile_update_title' => data_get($portal, 'profile.update_title'),

            'support_title' => data_get($portal, 'support.title'),
            'support_description' => data_get($portal, 'support.description'),
            'support_manager_title' => data_get($portal, 'support.manager_title'),
            'support_manager_role' => data_get($portal, 'support.manager_role'),
            'support_phone_label' => data_get($portal, 'support.phone_label'),
            'support_phone_note' => data_get($portal, 'support.phone_note'),
            'support_email_label' => data_get($portal, 'support.email_label'),
            'support_email_note' => data_get($portal, 'support.email_note'),
            'support_hours_label' => data_get($portal, 'support.hours_label'),
            'support_hours_value' => data_get($portal, 'support.hours_value'),
            'support_hours_note' => data_get($portal, 'support.hours_note'),
            'support_message_title' => data_get($portal, 'support.message_title'),
            'support_message_button' => data_get($portal, 'support.message_button'),
            'support_faq_title' => data_get($portal, 'support.faq_title'),
            'support_faqs_text' => collect(data_get($portal, 'support.faqs', []))
                ->map(fn ($faq) => ($faq['question'] ?? '').'|'.($faq['answer'] ?? ''))
                ->implode(PHP_EOL),
            'support_tickets_title' => data_get($portal, 'support.tickets_title'),
            'support_tickets_empty' => data_get($portal, 'support.tickets_empty'),
        ];
    }
}
