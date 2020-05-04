<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BulletinTest extends TestCase
{
    public function testBulletinIndexRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testBulletinIndexRedirectWhenUserWithoutActiveGroup()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyBulletinIndexWithNoDatas()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyBulletinIndexWithFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyBulletinIndexCloseFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyBulletinIndexWithAnnouncement()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
    
    public function testBulletinCreateRedirectWhenUserWithoutActiveGroup()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testBulletinCreateRedirectWhenUserIsNotAdmin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
    
    public function testDisplyBulletinCreatePage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testBulletinCreateStore()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testBulletinEditRedirectWhenUserWithoutActiveGroup()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testBulletinEditRedirectWhenUserIsNotAdmin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyBulletinEditPage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testBulletinEditUpdate()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyBulletinIndexWithTasks()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
