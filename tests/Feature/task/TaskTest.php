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
        $response = $this->get('/task');
        $response->assertRedirect('/login');
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/task');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testDisplyTaskWithNoDatas()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/task');
        $response->assertStatus(200);
    }

    public function testDisplyTaskWithUncompletedTasks()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);
        $category = $this->category($group->id);
        $task = $this->task($category->id, $user->id);
        $task->confirmed = 1;
        $task->save();
        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/task');
        $response->assertStatus(200)
                 ->assertSeeText('回報');
    }

    public function testDisplyTaskWithReportedTasks()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);
        $category = $this->category($group->id);
        $task = $this->task($category->id, $user->id);
        $task->confirmed = 1;
        $task->save();
        $user->active_group = $group->id;
        $user->save();

        $task->users()->attach($user->id, ['confirmed' => 0]);

        $response = $this->get('/task');
        $response->assertStatus(200)
                 ->assertSeeText('任務審核中');
    }

    public function testDisplyTaskWithTurnDownTasks()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);
        $category = $this->category($group->id);
        $task = $this->task($category->id, $user->id);
        $task->confirmed = 1;
        $task->save();
        $user->active_group = $group->id;
        $user->save();

        $task->users()->attach($user->id, ['confirmed' => -1]);

        $response = $this->get('/task');
        $response->assertStatus(200)
                 ->assertSeeText('任務遭駁回');
    }
}
