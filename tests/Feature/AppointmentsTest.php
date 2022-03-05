<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $response = $this->post(route('appointments.create'), [
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
                'end_date' => now()->addMinutes(29),
            ],
            [
                'start_date' => now()->addMinutes(30),
                'end_date' => now()->addMinutes(59),
            ]
        ]);

        $this->actingAs($user);

        $response = $this->post(route('appointments.create'), [
            'start_date' => now()->subMinutes(31)->toDateTimeString(),
            'patient_id' => $patient->id
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('patients', ['id' => $patient->id]);
        $this->assertDatabaseCount('appointments', 3);
        $this->assertDatabaseCount('patients', 3);
    }

}
