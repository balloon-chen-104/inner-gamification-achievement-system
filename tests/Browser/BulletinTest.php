<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BulletinTest extends DuskTestCase
{
    public function testDisplyBulletinIndexCloseFlashMessage()
    {
        $user = $this->user();
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);
        $user->active_group = $group->id;
        $user->save();
        $bulletin = $this->bulletin('flash_message', 'test content', $user->id, $group->id);
        
        $this->browse(function ($browser) use($user, $bulletin) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('@login-btn')
                    ->assertPathIs('/bulletin')
                    ->click('@close-flash-message-btn')
                    ->assertDontSee($bulletin->content);
        });

        $this->refreshDatabase();
    }
}
