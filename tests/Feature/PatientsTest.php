<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItReturnAllPatients()
    {

        $this->withoutExceptionHandling();
        $patients = User::factory(5)->create();

        $this->get(route('patients'))->assertViewIs('pages.patients.all-patients')->assertViewHasAll(['patients' => $patients]);

    }
}
