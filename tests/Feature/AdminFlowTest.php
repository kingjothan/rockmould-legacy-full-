<?php

namespace Tests\Feature;

use App\Models\ConsultationBooking;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFlowTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_admin_login_page_renders(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_admin_can_log_in_with_email_and_password(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();

        $this->post('/admin/login', [
            'email' => 'admin@rockmould.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_inactive_admin_cannot_log_in(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $admin->update(['status' => 'inactive']);

        $this->from('/admin/login')
            ->post('/admin/login', [
                'email' => 'admin@rockmould.com',
                'password' => 'password',
            ])
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_client_role_cannot_access_admin_dashboard(): void
    {
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();

        $this->actingAs($client)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_authenticated_admin_can_access_core_admin_pages(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $project = Project::query()->where('slug', 'azure-towers')->firstOrFail();
        $consultation = ConsultationBooking::query()->where('reference', 'CONS-SEEDED-001')->firstOrFail();

        $this->actingAs($admin);

        $routes = [
            '/admin',
            '/admin/clients',
            route('admin.clients.show', $client),
            '/admin/kyc',
            '/admin/consultations',
            route('admin.consultations.show', $consultation),
            '/admin/projects',
            route('admin.projects.show', $project),
            '/admin/payments',
            '/admin/documents',
            '/admin/media',
            '/admin/updates',
            '/admin/cms/homepage',
            '/admin/cms/pages',
            '/admin/cms/portal',
            '/admin/cms/about',
            '/admin/cms/services',
            '/admin/cms/projects',
            '/admin/cms/portfolio',
            '/admin/cms/blog',
            '/admin/cms/testimonials',
            '/admin/cms/faq',
            '/admin/cms/careers',
            '/admin/forms',
            '/admin/notifications',
            '/admin/settings',
            '/admin/roles',
            '/admin/activity',
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertOk();
        }
    }

    public function test_admin_can_update_system_typography_settings(): void
    {
        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'name' => 'Rockmould Limited',
                'tagline' => 'Premium Real Estate and Construction',
                'phone' => '+234 123 456 7890',
                'email' => 'info@rockmould.com',
                'address' => '12 Admiralty Way, Victoria Island, Lagos, Nigeria',
                'hours' => 'Mon - Fri: 9:00 AM - 6:00 PM',
                'support_phone' => '+234 801 234 5678',
                'support_email' => 'support@rockmould.com',
                'public_copy_scale' => '1.37',
                'public_heading_scale' => '1.42',
                'portal_copy_scale' => '1.53',
                'portal_heading_scale' => '1.31',
                'admin_copy_scale' => '1.19',
                'admin_heading_scale' => '1.47',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $company = Setting::query()->where('key', 'company')->firstOrFail()->value;

        $this->assertSame('1.37', $company['public_copy_scale']);
        $this->assertSame('1.42', $company['public_heading_scale']);
        $this->assertSame('1.53', $company['portal_copy_scale']);
        $this->assertSame('1.31', $company['portal_heading_scale']);
        $this->assertSame('1.19', $company['admin_copy_scale']);
        $this->assertSame('1.47', $company['admin_heading_scale']);

        $this->get('/')
            ->assertOk()
            ->assertSee('--public-copy-scale: 1.37;', false)
            ->assertSee('--public-heading-scale: 1.42;', false);

        $this->actingAs($admin)
            ->get('/admin/settings')
            ->assertOk()
            ->assertSee('--shell-copy-scale: 1.19;', false)
            ->assertSee('--shell-heading-scale: 1.47;', false);

        $this->actingAs($client)
            ->get('/portal/dashboard')
            ->assertOk()
            ->assertSee('--shell-copy-scale: 1.53;', false)
            ->assertSee('--shell-heading-scale: 1.31;', false);
    }
}
