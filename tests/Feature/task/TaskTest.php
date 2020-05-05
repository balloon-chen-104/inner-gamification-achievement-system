<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;

class TaskTest extends TestCase
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

    public function testDisplyTaskWithNoDatas()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testDisplyTaskWithUncompletedTasks()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testDisplyTaskWithCompletedTasks()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testDisplyTaskWithTurnDownTasks()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }
}
