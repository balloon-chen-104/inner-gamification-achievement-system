<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
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

    public function testRedirectWhenUserIdDoesNotInTheGroup()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyProfileIndexPageWhenUserViewOthersProfile()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
    
    public function testDisplyProfileIndexPageWhenUserViewTheirOwnProfile()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testProfileEditRedirectWhenUserViewOthersProfile()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyProfileEditPage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testProfileEditTextareaUpdate()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testProfileEditImageUpdate()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyProfileIndexWithTasksInfo()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

}
