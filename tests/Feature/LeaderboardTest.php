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
        $response->assertStatus(302);

        $response = $this->get('/login');
        $response->assertStatus(200);
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

    // 尚未完成1
    public function testDisplyLeaderboardIndexWithSortedRecords()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    // 尚未完成2#
    public function testRedirectToProfileButton()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
