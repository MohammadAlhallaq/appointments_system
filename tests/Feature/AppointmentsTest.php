<?php

namespace Tests\Feature;

use App\Enums\AppointmentInterval;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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
            'patient_id' => $patient->id
        ]);

        $response->assertUnprocessable();
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
        ]);

        $this->assertDatabaseHas('appointments', [
            'start_date' => now()->addMinutes(30)->toDateTimeString(),
            'end_date' => now()->addMinutes(60)->toDateTimeString(),
            'patient_id' => $patient->id
        ]);

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

        $response = $this->get(route('appointments'));

        $response->assertViewIs('pages.appointments.index')->assertViewHas('appointments');
    }

}

//"select exists(select * from `appointments` where (`start_date` between ? and ? or `end_date` between ? and ? or ((`start_date` < ? and `end_date` > ?)) a
//nd not (`end_date` = ?))) as `exists`"

//"select exists(select * from `appointments` where (`start_date` between ? and ? or `end_date` between ? and ? or ((`start_date` < ? and `end_date` > ?)))
//and not (`end_date` = ?))
