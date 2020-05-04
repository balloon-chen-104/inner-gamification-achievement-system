<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingTest extends TestCase
{
    public function testRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
