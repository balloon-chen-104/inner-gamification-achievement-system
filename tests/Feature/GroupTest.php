<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;
    
    public function testLoginFirstTimeSeeTwoForms()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testLoginFirstTimeMakeGroupAndRedirectAutomatically()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testLoginFirstTimeJoinGroupAndRedirectAutomatically()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testMakeGroupButNotRedirect()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testJoinGroupButNotRedirect()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testSwitchGroup()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }
}
