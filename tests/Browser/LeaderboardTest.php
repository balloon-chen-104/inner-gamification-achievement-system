<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LeaderboardTest extends DuskTestCase
{
    public function testRedirectToProfileButton()
    {
        $user = $this->user();
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);
        $user->active_group = $group->id;
        $user->save();
        
        $this->browse(function ($browser) use($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('@login-btn');

            $browser->visit('/leaderboard')  
                    ->click("@see-more-$user->id")
                    ->assertPathIs("/profile/$user->id");
        });
        
        $this->refreshDatabase();
    }
}
