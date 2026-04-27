<?php

namespace Tests\Feature;

use App\Mail\ConsultationBookedAdminMail;
use App\Mail\ConsultationBookedClientMail;
use App\Mail\ConsultationBookingUpdatedMail;
use App\Models\ConsultationBooking;
use App\Models\User;
use App\Services\Consultations\ConsultationAvailabilityService;
use Database\Seeders\RockmouldSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ConsultationBookingTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected string $seeder = RockmouldSeeder::class;

    public function test_public_booking_flow_creates_a_consultation_and_sends_emails(): void
    {
        Mail::fake();

        $slot = $this->nextAvailableSlot();

        $response = $this->post(route('consultations.store'), [
            'consultation_type' => 'project_consultation',
            'consultation_date' => $slot['date'],
            'start_time' => $slot['start_time'],
            'full_name' => 'Test Booker',
            'email' => 'booker@example.com',
            'phone' => '+2348001112233',
            'company_name' => 'Summit Crest',
            'subject' => 'Premium residential consultation',
            'message' => 'I would like to discuss project availability and investment terms.',
            'timezone' => 'Africa/Lagos',
        ]);

        $booking = ConsultationBooking::query()->where('email', 'booker@example.com')->firstOrFail();

        $response->assertRedirect(route('consultations.success', $booking->reference));

        $this->assertDatabaseHas('consultation_bookings', [
            'email' => 'booker@example.com',
            'consultation_type' => 'project_consultation',
            'start_time' => $slot['start_time'].':00',
        ]);
        $this->assertSame($slot['date'], $booking->consultation_date?->format('Y-m-d'));

        Mail::assertSent(ConsultationBookedClientMail::class, fn ($mail) => $mail->booking->is($booking));
        Mail::assertSent(ConsultationBookedAdminMail::class, fn ($mail) => $mail->booking->is($booking));
    }

    public function test_booking_conflicts_are_rejected(): void
    {
        $slot = $this->nextAvailableSlot();

        ConsultationBooking::query()->create([
            'reference' => 'CONS-LOCKED-001',
            'consultation_type' => 'property_inquiry',
            'full_name' => 'Existing Booker',
            'email' => 'existing@example.com',
            'phone' => '+2348009990000',
            'subject' => 'Existing slot',
            'message' => 'This booking should reserve the slot.',
            'consultation_date' => $slot['date'],
            'start_time' => $slot['start_time'].':00',
            'end_time' => $slot['end_time'].':00',
            'timezone' => 'Africa/Lagos',
            'status' => ConsultationBooking::STATUS_PENDING,
        ]);

        $this->from(route('consultations.create'))
            ->post(route('consultations.store'), [
                'consultation_type' => 'property_inquiry',
                'consultation_date' => $slot['date'],
                'start_time' => $slot['start_time'],
                'full_name' => 'Blocked Booker',
                'email' => 'blocked@example.com',
                'phone' => '+2348002223344',
                'subject' => 'Conflicting slot',
                'message' => 'This should be rejected.',
                'timezone' => 'Africa/Lagos',
            ])
            ->assertRedirect(route('consultations.create'))
            ->assertSessionHasErrors('start_time');
    }

    public function test_admin_can_update_a_consultation_and_notify_the_client(): void
    {
        Mail::fake();

        $admin = User::query()->where('email', 'admin@rockmould.com')->firstOrFail();
        $booking = ConsultationBooking::query()->where('reference', 'CONS-SEEDED-001')->firstOrFail();
        $slot = $this->nextAvailableSlot($booking->id, 9);

        $this->actingAs($admin)
            ->put(route('admin.consultations.update', $booking), [
                'consultation_type' => 'investment_discussion',
                'full_name' => $booking->full_name,
                'email' => $booking->email,
                'phone' => $booking->phone,
                'company_name' => $booking->company_name,
                'subject' => 'Updated investment review',
                'message' => 'Updated schedule and meeting link attached.',
                'consultation_date' => $slot['date'],
                'start_time' => $slot['start_time'],
                'timezone' => 'Africa/Lagos',
                'status' => ConsultationBooking::STATUS_CONFIRMED,
                'assigned_staff_id' => $admin->id,
                'meeting_link' => 'https://meet.google.com/updated-rockmould',
                'admin_notes' => 'Investor confirmed for revised session.',
            ])
            ->assertRedirect();

        $booking->refresh();

        $this->assertSame('investment_discussion', $booking->consultation_type);
        $this->assertSame(ConsultationBooking::STATUS_CONFIRMED, $booking->status);
        $this->assertSame('https://meet.google.com/updated-rockmould', $booking->meeting_link);
        $this->assertSame($slot['start_time'].':00', $booking->start_time);

        Mail::assertSent(ConsultationBookingUpdatedMail::class, fn ($mail) => $mail->booking->is($booking));
    }

    protected function nextAvailableSlot(?int $excludeBookingId = null, int $startOffset = 1): array
    {
        $availability = app(ConsultationAvailabilityService::class);

        for ($i = $startOffset; $i <= 45; $i++) {
            $date = now('Africa/Lagos')->addDays($i)->toDateString();
            $slots = $availability->slotsForDate($date, $excludeBookingId);

            if ($slots !== []) {
                return $slots[0];
            }
        }

        $this->fail('No consultation slots were available during the test window.');
    }
}
