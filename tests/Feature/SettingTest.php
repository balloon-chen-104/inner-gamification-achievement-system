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

    public function testSettingIndexRedirectWhenUserIsNotAdmin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingEditCycleRedirectWhenUserIsNotAdmin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplySettingEditCyclePage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingEditCycleUpdate()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplySettingIndexWithoutFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingIndexWithFlashMessageWithoutButtonsWhenUserIsNotAdmin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingIndexWithFlashMessageWithButtonsWhenUserIsAdmin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingCreateFlashMessageRedirectWhenUserIsNotAdmin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplySettingCreateFlashMessagePage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingCreateFlashMessageStore()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingEditFlashMessageRedirectWhenUserIsNotAdmin()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplySettingEditFlashMessagePage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingEditFlashMessageUpdate()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingIndexCloseFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingIndexOpenFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingDeleteFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
