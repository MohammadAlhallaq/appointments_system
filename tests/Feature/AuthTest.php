<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItViewLoginPage()
    {
        $response = $this->get(route('login'));
        $response->assertInertia(fn(Assert $page) => $page->component('auth/login'));
        $response->assertStatus(200);
    }



    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItRedirectsToHomeIfNotGuest()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('login'))->assertRedirect(route('home'));
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItReturnsLoginUsernameValidationErrors()
    {
        $response = $this->post(route('login'),
            [
                'username' => 'invalid',
                'password' => 'password'
            ]);

        $response->assertSessionHasErrors(['credentials']);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItReturnsLoginPasswordValidationErrors()
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'),
            [
                'username' => $user->username,
                'password' => 'invalid'
            ]);

        $response->assertSessionHasErrors(['credentials']);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItLogsInTheUser()
    {
        $user = User::factory()->create();

        $this->post(route('login'),
            [
                'username' => $user->username,
                'password' => 'password'
            ])
        ->assertInertia(fn(Assert $page) => $page->component('home'));

        $this->assertAuthenticated();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ItLogsOutTheUser()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('logout'));

        $response->assertRedirect(route('login'));
    }
}
