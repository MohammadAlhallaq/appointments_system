<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ViewLoginPage()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ReturnsLoginValidationErrors()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }
}
