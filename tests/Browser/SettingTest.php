<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SettingTest extends DuskTestCase
{
    public function testSettingCreateFlashMessageStore()
    {
        $this->artisan('migrate:fresh');
        
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

            $browser->visit('/setting/createFlashMessage')
                    ->assertSee('新增快訊')
                    ->type('flash-message-content', 'test content')
                    ->press('@flash-message-submit');

            $browser->visit('/bulletin')
                    ->assertSee('test content');
        });
        
        $this->artisan('migrate:fresh');
    }

    // // 尚未完成
    // public function testSettingIndexOpenFlashMessage()
    // {
    //     // $bulletin = $this->bulletin('flash_message', 'test content', 1, 2);
        
    //     $this->browse(function ($browser) {
    //         // // 上面登入狀態仍然保持
    //         // $browser->visit('/login')
    //         //         ->type('email', $user->email)
    //         //         ->type('password', 'password')
    //         //         ->press('@login-btn');
            
    //         $browser->visit('/setting')
    //                 ->assertSee('快訊開關設定');
    //                 // ->check("flash-message-switch-btn-1");

    //         // $browser->visit('/bulletin')  
    //         //         ->assertSee($bulletin->content);
    //     });
    // }

    // // 尚未完成
    // public function testSettingIndexCloseFlashMessage()
    // {
    //     $this->browse(function ($browser) {
    //         $content = 'content A';

    //         // // 上面登入狀態仍然保持
    //         // $browser->visit('/login')
    //         //         ->type('email', $user->email)
    //         //         ->type('password', 'password')
    //         //         ->press('@login-btn');
            
    //         $browser->visit('/setting')
    //                 ->assertSee('快訊開關設定')
    //                 ->check('flash-message-switch-btn');

    //         // $browser->visit('/bulletin')  
    //         //         ->assertDontSee($content);
    //     });
    // }
}
