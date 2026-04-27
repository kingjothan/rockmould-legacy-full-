<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ConsultationModuleResilienceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_consultation_module_degrades_gracefully_when_tables_are_missing(): void
    {
        $this->dropConsultationTables();

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();

        $this->get(route('consultations.create'))
            ->assertOk()
            ->assertSee('Consultation scheduling is temporarily unavailable.', false);

        $this->getJson(route('consultations.availability', ['month' => now()->format('Y-m')]))
            ->assertStatus(503)
            ->assertJson([
                'message' => 'Consultation scheduling is temporarily unavailable while the booking calendar is being finalized.',
            ]);

        $this->from(route('consultations.create'))
            ->post(route('consultations.store'), [
                'consultation_type' => 'project_consultation',
                'consultation_date' => now()->addDays(3)->format('Y-m-d'),
                'start_time' => '10:00',
                'full_name' => 'Fallback Booker',
                'email' => 'fallback@example.com',
                'phone' => '+2348000000000',
                'subject' => 'Manual consultation support',
                'message' => 'Please help schedule this manually.',
                'timezone' => 'Africa/Lagos',
            ])
            ->assertRedirect(route('consultations.create'))
            ->assertSessionHasErrors('consultation');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.consultations.index'))
            ->assertOk()
            ->assertSee('Run', false)
            ->assertSee('php artisan migrate', false);

        $this->actingAs($admin)
            ->get('/admin/consultations/1')
            ->assertNotFound();
    }

    protected function dropConsultationTables(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'consultation_bookings',
            'consultation_blocked_dates',
            'consultation_availabilities',
            'consultation_settings',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }
}
