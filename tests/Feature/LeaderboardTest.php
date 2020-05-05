<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;
    
    public function testRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/leaderboard');
        $response->assertRedirect('/login');
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/leaderboard');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testDisplyLeaderboardIndexWithOneRecord()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/leaderboard');
        $response->assertStatus(200)
                 ->assertSeeText($user->name);
    }

    public function testDisplyLeaderboardIndexWithSortedRecords()
    {
        $user1 = $this->user();
        $user2 = $this->user();
        Auth::login($user1, true);
        
        $group = $this->group($user1->id);
        $group->users()->attach($user1->id, ['authority' => 1]);
        $group->users()->attach($user2->id, ['authority' => 0]);

        $user1->active_group = $group->id;
        $user1->save();
        $user2->active_group = $group->id;
        $user2->save();

        $category = $this->category($group->id);
        $task = $this->task($category->id, $user1->id);
        $task->confirmed = 1;
        $task->save();
        $task->users()->attach($user2->id, ['confirmed' => 1]);

        $response = $this->get('/leaderboard');
        $response->assertStatus(200)
                 ->assertSeeText($user1->name)
                 ->assertSeeText($user2->name)
                 ->assertSeeTextInOrder([$user2->name, $user1->name], $escaped = true);
    }

    // 尚未完成
    public function testRedirectToProfileButton()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
