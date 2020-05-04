<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeaderboardTest extends TestCase
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

    public function testDisplyLeaderboardIndexWithNoDatas()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyLeaderboardIndexWithOneRecord()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyLeaderboardIndexWithSortedRecords()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testRedirectToProfileButton()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
