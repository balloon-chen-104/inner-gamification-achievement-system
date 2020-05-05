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
        $response = $this->get('/task/history');
        $response->assertRedirect('/login');
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/task/history');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testDisplyTaskHistoryWithNoDatas()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/task/history');
        $response->assertStatus(200)
                 ->assertSeeText('目前沒有已到期任務');
    }

    public function testDisplyTaskHistoryWithTasks()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);
        $category = $this->category($group->id);
        $task = $this->task($category->id, $user->id, -2);
        $task->confirmed = 1;
        $task->save();
        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/task/history');
        $response->assertStatus(200)
                 ->assertSeeText($task->name);
    }
}
