<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;

class TaskHistoryTest extends TestCase
{
    use RefreshDatabase;
    
    public function testRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/setting');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testDisplyTaskHistoryWithNoDatas()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testDisplyTaskHistoryWithTasks()
    {
        // 看到回報，不能點
        $response = $this->get('/');
        $response->assertStatus(302);
    }
}
