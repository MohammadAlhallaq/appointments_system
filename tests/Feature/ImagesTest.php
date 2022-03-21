<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImagesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * @return void
     * @test
     */
    function ItDoseNotAddImagesToNonExistingPatient()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('images.create', 1))->assertNotFound();
    }


    /**
     * A basic feature test example.
     * @return void
     * @test
     */
    function ItReturnsValidationErrors()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $this->actingAs($user);

        $this->post(route('images.create', $patient->id),
            ['images' => $image]
        )->assertSessionHasErrors('images');
    }


    /**
     * A basic feature test example.
     * @return void
     * @test
     */
    function ItCreatesImageForThePatient()
    {
        $this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('cover.jpg');
        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('images.create', $patient->id),
            ['images' => [$image]]
        );

        $response->assertRedirect(route('patients'));
        $this->assertDatabaseCount('images', 1);
        $this->assertDatabaseHas('images', ['patient_id'=>$patient->id]);
    }
}
