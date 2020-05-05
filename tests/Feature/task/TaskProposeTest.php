<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;

class TaskProposeTest extends TestCase
{
    use RefreshDatabase;
    
    public function testRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/task/propose');
        $response->assertRedirect('/login');
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/task/propose');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testRedirectWhenUserIsAdmin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/task/propose');
        $response->assertRedirect('/task/verify');
    }

    public function testDisplyTaskProposeWithNoDatas()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/task/propose');
        $response->assertStatus(200)
                 ->assertSeeText('目前沒有任何任務');
    }

    // 尚未完成
    public function testProposeTask()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    // 尚未完成
    public function testEditTurnDownTask()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    public function testDisplyTaskProposeWithPassProposeTask()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 0]);
        $category = $this->category($group->id);
        $task = $this->task($category->id, $user->id);
        $task->confirmed = 1;
        $task->save();
        $user->active_group = $group->id;
        $user->save();
        
        $response = $this->get('/task/propose');

        $response->assertStatus(200)
                 ->assertSeeText($task->name)
                 ->assertSeeText('已通過');
    }
}
