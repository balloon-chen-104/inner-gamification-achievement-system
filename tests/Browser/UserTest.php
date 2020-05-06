<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
{
    // 尚未完成
    public function testDisplyBulletinIndexCloseFlashMessage()
    {
        $this->browse(function ($browser) {
            $browser->visit('/login')
                    ->press('@azure-login-btn');

            $browser->visit('/login')
                    ->assertSee('登入');
        });
    }
}
