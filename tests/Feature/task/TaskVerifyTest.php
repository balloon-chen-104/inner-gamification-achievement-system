<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;

class TaskVerifyTest extends TestCase
{
    use RefreshDatabase;
    
    public function testRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/task/verify');
        $response->assertRedirect('/login');
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/task/verify');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testRedirectWhenUserIsNotAdmin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/task/verify');
        $response->assertRedirect('/task');
    }

    public function testDisplyTaskProposeWithNoDatas()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/task/verify');
        $response->assertStatus(200)
                 ->assertSeeText('目前沒有待審核任務')
                 ->assertSeeText('目前沒有人完成任務');
    }

    // 尚未完成
    public function testPassTask()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    // 尚未完成
    public function testTurnDownTask()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }
}
