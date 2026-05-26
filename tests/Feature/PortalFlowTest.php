<?php

namespace Tests\Feature;

use App\Models\ProjectAssignment;
use App\Models\ClientOtp;
use App\Models\Project;
use App\Models\User;
use App\Mail\ClientPortalOtpMail;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortalFlowTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_portal_login_page_renders(): void
    {
        $this->get('/portal')->assertOk();
    }

    public function test_kyc_form_uses_project_dropdown(): void
    {
        $project = Project::query()->published()->firstOrFail();

        $this->get('/portal?mode=kyc')
            ->assertOk()
            ->assertSee('name="project_id"', false)
            ->assertSee($project->title);
    }

    public function test_active_client_can_log_in_with_email_only_after_otp_verification(): void
    {
        Mail::fake();
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();

        $this->post('/portal/login', [
            'email' => 'ADEBAYO@example.com',
        ])->assertRedirect(route('portal.login'))
            ->assertSessionHas('portal_state', 'otp');

        $this->assertGuest();
        $this->assertDatabaseHas('client_otps', [
            'user_id' => $client->id,
            'email' => 'adebayo@example.com',
            'phone' => 'adebayo@example.com',
            'delivery_channel' => 'email',
            'delivery_status' => 'sent',
        ]);
        Mail::assertSent(ClientPortalOtpMail::class, fn (ClientPortalOtpMail $mail) => $mail->hasTo('adebayo@example.com'));

        $this->post(route('portal.otp.verify'), [
            'otp' => '123456',
        ])->assertRedirect(route('portal.dashboard'));

        $this->assertAuthenticatedAs($client);
    }

    public function test_invalid_otp_does_not_authenticate_client(): void
    {
        Mail::fake();

        $this->post('/portal/login', [
            'email' => 'adebayo@example.com',
        ]);

        $this->post(route('portal.otp.verify'), [
            'otp' => '000000',
        ])->assertRedirect(route('portal.login'))
            ->assertSessionHasErrors('otp');

        $this->assertGuest();
        $this->assertSame(1, ClientOtp::query()->latest()->firstOrFail()->attempts);
    }

    public function test_unrecognized_email_returns_kyc_state(): void
    {
        $this->from('/portal')->post('/portal/login', [
            'email' => 'unknown@example.com',
        ])->assertRedirect('/portal')
            ->assertSessionHas('portal_state', 'unrecognized');
    }

    public function test_inactive_client_email_returns_pending_state_and_error(): void
    {
        $this->from('/portal')->post('/portal/login', [
            'email' => 'folake@example.com',
        ])->assertRedirect('/portal')
            ->assertSessionHas('portal_state', 'pending')
            ->assertSessionHasErrors('email');
    }

    public function test_kyc_submission_creates_a_request(): void
    {
        Storage::persistentFake('public');
        $project = Project::query()->published()->firstOrFail();

        $this->from('/portal')->post('/portal/kyc', [
            'full_name' => 'New Prospect',
            'phone' => '+2348055555555',
            'email' => 'prospect@example.com',
            'residential_address' => 'Lekki Phase 1, Lagos',
            'project_id' => $project->id,
            'note' => 'Please review my KYC request.',
            'identification' => UploadedFile::fake()->create('id-card.pdf', 120, 'application/pdf'),
        ])->assertRedirect('/portal')
            ->assertSessionHas('portal_state', 'pending');

        $this->assertDatabaseHas('kyc_requests', [
            'email' => 'prospect@example.com',
            'project_id' => $project->id,
            'project_of_interest' => $project->title,
            'status' => 'pending',
        ]);
    }

    public function test_authenticated_client_can_access_portal_pages(): void
    {
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $ownedAssignment = $client->assignments()->firstOrFail();

        $this->actingAs($client);

        $this->get('/portal/dashboard')->assertOk();
        $this->get('/portal/projects')->assertOk();
        $this->get(route('portal.projects.show', $ownedAssignment))->assertOk();
        $this->get('/portal/documents')->assertOk();
        $this->get('/portal/media')->assertOk();
        $this->get('/portal/balance')->assertOk();
        $this->get('/portal/updates')->assertOk();
        $this->get('/portal/profile')->assertOk();
        $this->get('/portal/support')->assertOk();
    }

    public function test_client_cannot_open_another_clients_assignment(): void
    {
        $client = User::query()->where('email', 'adebayo@example.com')->firstOrFail();
        $foreignAssignment = ProjectAssignment::query()
            ->whereHas('user', fn ($query) => $query->where('email', 'chioma@example.com'))
            ->firstOrFail();

        $this->actingAs($client)
            ->get(route('portal.projects.show', $foreignAssignment))
            ->assertForbidden();
    }
}
