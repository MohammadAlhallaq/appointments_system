<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AppointmentsTest extends TestCase
{
    use RefreshDatabase;


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItCantOverlapAppointment()
    {
        $user = User::factory()->create();

        $patient = Patient::factory()->create();

        Appointment::factory()->createMany([
            [
                'start_date' => now(),
                'end_date' => now()->addMinutes(29),
            ],
            [
                'start_date' => now()->addMinutes(30),
                'end_date' => now()->addMinutes(59),
            ]
        ]);

        $this->actingAs($user);

        $response = $this->post(route('appointments.store'), [
            'start_date' => now()->addMinutes(30)->toDateTimeString(),
            'end_date' => now()->addMinutes(35)->toDateTimeString(),
            'patient_id' => $patient->id
        ]);


        $response->assertSessionHasErrors(['appointment']);
        $this->assertDatabaseHas('patients', ['id' => $patient->id]);
        $this->assertDatabaseCount('appointments', 2);
        $this->assertDatabaseCount('patients', 3);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItCanCreateAnAppointment()
    {
        $user = User::factory()->create();

        $patient = Patient::factory()->create();

        Appointment::factory()->createMany([
            [
                'start_date' => now(),
                'end_date' => now()->addMinutes(15),
            ],
            [
                'start_date' => now()->addMinutes(16),
                'end_date' => now()->addMinutes(20),
            ]
        ]);

        $this->actingAs($user);

        $response = $this->post(route('appointments.store'), [
            'start_date' => now()->addMinutes(21)->toDateTimeString(),
            'end_date' => now()->addMinutes(25)->toDateTimeString(),
            'patient_id' => $patient->id,
        ]);


        $response->assertRedirect(route('appointments'));
        $this->assertDatabaseHas('patients', ['id' => $patient->id]);
        $this->assertDatabaseCount('appointments', 3);
        $this->assertDatabaseCount('patients', 3);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItValidatesThatEndDateIsAfterStartDate()
    {
        $user = User::factory()->create();

        $patient = Patient::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('appointments.store'), [
            'start_date' => now()->addMinutes(21)->toDateTimeString(),
            'end_date' => now()->addMinutes(20)->toDateTimeString(),
            'patient_id' => $patient->id,
        ]);

        $response->assertSessionHasErrors(['end_date']);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItOnlyUpdateAppointmentsForTheSelectedPatient()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $patient1 = Patient::factory()->create();
        $appointment = Appointment::factory()->create(
            [
                'start_date' => now(),
                'end_date' => now()->addMinutes(29),
                'patient_id' => $patient->id
            ],
        );

        $this->actingAs($user);

        $response = $this->put(route('appointments.update', ['patient' => $patient1->id, 'appointment' => $appointment->id]));

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->status());
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItUpdatesThePatientAppointment()
    {

        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $appointment = Appointment::factory()->create(
            [
                'start_date' => now(),
                'end_date' => now()->addMinutes(29),
                'patient_id' => $patient->id
            ],
        );

        $this->actingAs($user);

        $response = $this->put(route('appointments.update',
            ['patient' => $patient->id, 'appointment' => $appointment->id]), [
            'start_date' => now()->addMinutes(30),
            'end_date' => now()->addMinutes(60),
        ]);

        $this->assertDatabaseHas('appointments', [
            'start_date' => now()->addMinutes(30)->toDateTimeString(),
            'end_date' => now()->addMinutes(60)->toDateTimeString(),
            'patient_id' => $patient->id
        ]);

        $this->assertDatabaseHas('appointments', ['start_date' => now()->addMinutes(30)->toDateTimeString()]);
        $this->assertDatabaseHas('appointments', ['end_date' => now()->addMinutes(60)->toDateTimeString()]);
        $response->assertRedirect(route('appointments'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */

    function itReturnsAllAppointments()
    {

        $user = User::factory()->create();
        Appointment::factory(10)->create();

        $this->actingAs($user);

        $this->get(route('appointments'))->
        assertInertia(fn(Assert $page) => $page
            ->component('appointments/index')
            ->has('appointments', 10));

    }


    /**
     *
     * @return void
     * @test
     */
    function itCancelAnAppointments()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create();

        $this->actingAs($user);

        $response = $this->delete(route('appointments.delete', $appointment->id));

        $this->assertDatabaseMissing('appointments', ['id' => $appointment->id]);
        $this->assertDatabaseCount('appointments', 0);
        $response->assertRedirect(route('appointments'));
    }

}
