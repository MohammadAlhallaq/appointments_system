<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $user = User::factory()->create();
        Patient::factory(5)->create();

        $this->actingAs($user);

        $this->get(route('patients'))
            ->assertViewIs('pages.patients.index')
            ->assertViewHas('patients');
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItReturnCreatePatientView()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('patients.create'))
            ->assertOk()
            ->assertViewIs('pages.patients.create');
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItRedirectsToLogInRoute()
    {
        Patient::factory(5)->create();

        $response = $this->get(route('patients'));

        $response->assertRedirect(route('login'));
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItReturnValidationErrors()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('patients.store'));

        $response->assertJsonValidationErrors(['last_name', 'first_name', 'phone_number', 'address']);
    }

    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItCreatesNewPatient()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('patients.store'), [
            'first_name' => 'khaled',
            'last_name' => 'mohammad',
            'address' => 'address address address',
            'notes' => ' no notes',
            'phone_number' => '00963552349',
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('patients', 1);
        $this->assertDatabaseHas('patients', ['first_name' => 'khaled']);
    }

    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItAddPatientWithOneImage()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('patients.store'), [
            'first_name' => 'khaled',
            'last_name' => 'mohammad',
            'address' => 'address address address',
            'notes' => ' no notes',
            'phone_number' => '00963552349',
            'images' => [$image],
        ]);

        $response->assertOk();
        $patient = Patient::all('id')->first();
        $this->assertTrue($patient->images()->exists());
        $this->assertDatabaseCount('patients', 1);
        $this->assertDatabaseHas('patients', ['first_name' => 'khaled']);
        $this->assertDatabaseHas('images', ['path' => 'images/' . $image->hashName(), 'patient_id' => $patient->id]);
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItAddPatientWithMultipleImages()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $image1 = UploadedFile::fake()->image('cover1.jpg');

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('patients.store'), [
            'first_name' => 'khaled',
            'last_name' => 'mohammad',
            'address' => 'address address address',
            'notes' => ' no notes',
            'phone_number' => '00963552349',
            'images' => [$image, $image1],
        ]);

        $patient = Patient::all('id')->first();
        $this->assertTrue($patient->images()->exists());
        $response->assertOk();
        $this->assertDatabaseCount('images', 2);
        $this->assertDatabaseCount('patients', 1);
        $this->assertDatabaseHas('patients', ['first_name' => 'khaled']);
        $this->assertDatabaseHas('images', ['path' => 'images/' . $image->hashName(), 'patient_id' => $patient->id]);
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItValidateThatImagesMustBeArray()
    {
        $image = UploadedFile::fake()->image('cover.jpg');

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('patients.store'), [
            'first_name' => 'khaled',
            'last_name' => 'mohammad',
            'address' => 'address address address',
            'notes' => ' no notes',
            'phone_number' => '00963552349',
            'images' => $image,
        ]);
        $response->assertJsonValidationErrors(['images']);
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItShowPatient()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $image1 = UploadedFile::fake()->image('cover1.jpg');

        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $patient->images()->createMany([
            ['path' => 'images/' . $image1->hashName()],
            ['path' => 'images/' . $image->hashName()]
        ]);
        $this->actingAs($user);

        $response = $this->get(route('patients.show', $patient->id));

        $response->assertViewIs('pages.patients.show')->assertViewHas(['patient', 'patient.images']);
        $this->assertDatabaseCount('images', 2);
        $this->assertDatabaseCount('patients', 1);
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItEditPatient()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $image1 = UploadedFile::fake()->image('cover1.jpg');

        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $patient->images()->createMany([
            ['path' => 'images/' . $image1->hashName()],
            ['path' => 'images/' . $image->hashName()]
        ]);
        $this->actingAs($user);

        $response = $this->put(route('patients.update', $patient->id), [
            'first_name' => 'edited',
            'last_name' => 'edited',
        ]);

        $response->assertViewIs('pages.patients.index');
        $this->assertDatabaseHas('patients', ['first_name' => 'edited', 'last_name' => 'edited']);
        $this->assertDatabaseCount('patients', 1);
    }



    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItReturnValidationErrorsForUpdating()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $image1 = UploadedFile::fake()->image('cover1.jpg');

        $user = User::factory()->create();
        $patient = Patient::factory()->create();
        $patient->images()->createMany([
            ['path' => 'images/' . $image1->hashName()],
            ['path' => 'images/' . $image->hashName()]
        ]);
        $this->actingAs($user);

        $response = $this->put(route('patients.update', $patient->id), [
            'first_name' => '',
            'last_name' => '',
        ]);

        $response->assertJsonValidationErrors(['first_name', 'last_name']);
        $this->assertDatabaseHas('patients', ['first_name' => $patient->first_name, 'last_name' => $patient->last_name]);
        $this->assertDatabaseCount('patients', 1);
    }




    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItDeletePatient()
    {
        $image = UploadedFile::fake()->image('image.jpg');
        $image1 = UploadedFile::fake()->image('image1.jpg');

        Storage::disk('public')->put('images/' . $image->hashName(), 'empty');
        Storage::disk('public')->put('images/' . $image1->hashName(), 'empty');

        $user = User::factory()->create();
        $patient = Patient::factory()->create();

        $patient->images()->createMany([
            ['path' => 'images/' . $image1->hashName()],
            ['path' => 'images/' . $image->hashName()]
        ]);

        $this->actingAs($user);

        $response = $this->Delete(route('patients.delete', $patient->id));

        $this->assertDatabaseMissing('patients', $patient->toArray());
        $this->assertDatabaseCount('patients', 0);
        $this->assertDatabaseCount('images', 0);
    }

}
