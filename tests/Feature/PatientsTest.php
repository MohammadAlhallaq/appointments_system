<?php

namespace Tests\Feature;

use App\Enums\AccountType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        $user = User::factory()->admin()->create();
        User::factory(5)->create();

        $this->actingAs($user);

        $this->get(route('patients'))
            ->assertViewIs('pages.patients.all-patients')
            ->assertViewHas('patients');
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItRedirectsToLogInRoute()
    {
        User::factory(5)->create();

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
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $response = $this->post(route('patients.new'));

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
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $response = $this->post(route('patients.new'), [
            'first_name' => 'khaled',
            'last_name' => 'mohammad',
            'address' => 'address address address',
            'notes' => ' no notes',
            'phone_number' => '00963552349',
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['first_name' => 'khaled']);
    }

    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function PatientWithOneImage()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $user = User::factory()->admin()->create();
        $this->actingAs($user);

        $response = $this->post(route('patients.new'), [
            'first_name' => 'khaled',
            'last_name' => 'mohammad',
            'address' => 'address address address',
            'notes' => ' no notes',
            'phone_number' => '00963552349',
            'images' => [$image],
        ]);

        $response->assertOk();
        $user = User::where('account_type', AccountType::PATIENT)->get()->first();
        $this->assertTrue($user->images()->exists());
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['first_name' => 'khaled']);
        $this->assertDatabaseHas('images', ['path' => 'images/' . $image->hashName(), 'user_id' => $user->id]);
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ItCreatesNewPatientWithMultipleImages()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $image1 = UploadedFile::fake()->image('cover1.jpg');

        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $response = $this->post(route('patients.new'), [
            'first_name' => 'khaled',
            'last_name' => 'mohammad',
            'address' => 'address address address',
            'notes' => ' no notes',
            'phone_number' => '00963552349',
            'images' => [$image, $image1],
        ]);


        $user = User::where('account_type', AccountType::PATIENT)->get()->first();
        $this->assertTrue($user->images()->exists());
        $response->assertOk();
        $this->assertDatabaseCount('images', 2);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['first_name' => 'khaled']);
        $this->assertDatabaseHas('images', ['path' => 'images/' . $image->hashName(), 'user_id' => $user->id]);
    }


    /**
     * A basic test example.
     *
     * @return void
     * @test
     */
    public function ImagesMustBeArray()
    {
        $image = UploadedFile::fake()->image('cover.jpg');
        $image1 = UploadedFile::fake()->image('cover1.jpg');

        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $response = $this->post(route('patients.new'), [
            'first_name' => 'khaled',
            'last_name' => 'mohammad',
            'address' => 'address address address',
            'notes' => ' no notes',
            'phone_number' => '00963552349',
            'images' => $image,
        ]);

        $response->assertJsonValidationErrors(['images']);
    }

}
