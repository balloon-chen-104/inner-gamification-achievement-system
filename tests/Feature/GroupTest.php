<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;

class GroupTest extends TestCase
{
    use RefreshDatabase;
    
    public function testLoginFirstTimeSeeTwoForms()
    {
        $user = $this->user();
        Auth::login($user, true);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('群組名稱')
                 ->assertSeeText('輸入群組 ID');
    }

    // 尚未完成
    public function testLoginFirstTimeMakeGroupAndRedirectAutomatically()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    // 尚未完成
    public function testLoginFirstTimeJoinGroupAndRedirectAutomatically()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    // 尚未完成
    public function testMakeGroupButNotRedirect()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    // 尚未完成
    public function testJoinGroupButNotRedirect()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    // 尚未完成
    public function testSwitchGroup()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }
}
