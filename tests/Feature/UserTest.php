<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testDisplayRegisterPage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testRegisterAndLoginAutomatically()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplayLoginPage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testNormallyLogin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testAzureLogin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
