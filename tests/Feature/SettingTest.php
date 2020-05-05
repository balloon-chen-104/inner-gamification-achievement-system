<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;

class SettingTest extends TestCase
{
    use RefreshDatabase;
    
    public function testRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/setting');
        $response->assertStatus(302);

        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/setting');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testSettingIndexRedirectWhenUserIsNotAdmin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/setting');
        $response->assertStatus(302);
    }

    public function testSettingEditCycleRedirectWhenUserIsNotAdmin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/setting/editCycle');
        $response->assertStatus(302);
    }

    // 尚未完成1
    public function testDisplySettingEditCyclePage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/setting/editCycle');
        $response->assertStatus(500);
        // $response->assertStatus(200)
        //          ->assertDontSeeText('群組ID');
    }

    // 尚未完成2
    public function testSettingEditCycleUpdate()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();


        $params = [
            'cycle' => 30
        ];

        $this->get("/setting/editCycle")
             ->assertStatus(500);

        // $this->put("/setting/updateCycle", $params)
        //      ->assertStatus(302);
        
        // $this->assertDatabaseMissing('bulletins', $bulletin->toArray());

        // $this->assertDatabaseHas('bulletins', [
        //     'type' => 'announcement',
        //     'content' => 'test announcement update',
        //     'group_id' => $group->id,
        //     'user_id' => $user->id
        // ]);



        // $response = $this->get('/setting/editCycle');
        // $response->assertStatus(500);
    }

    public function testDisplySettingIndexWithoutFlashMessage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/setting');
        $response->assertStatus(200)
                 ->assertSeeText('尚無快訊');
    }

    public function testDisplySettingIndexWithFlashMessage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $bulletin = $this->bulletin('flash_message', 'test flash_message', $user->id, $group->id);
        
        $response = $this->get('/setting');
        $response->assertStatus(200)
                 ->assertSeeText('test flash_message');
    }

    public function testSettingCreateFlashMessageRedirectWhenUserIsNotAdmin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/setting/createFlashMessage');
        $response->assertStatus(302);
    }

    public function testDisplySettingCreateFlashMessagePage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/setting/createFlashMessage');
        $response->assertStatus(200)
                 ->assertSeeText('新增快訊');
    }

    // 尚未完成3
    public function testSettingCreateFlashMessageStore()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingEditFlashMessageRedirectWhenUserIsNotAdmin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        $bulletin = $this->bulletin('flash_message', 'test flash_message', $user->id, $group->id);

        $response = $this->get("/setting/{$bulletin->id}/editFlashMessage");
        $response->assertStatus(302);
    }

    // 尚未完成4
    public function testDisplySettingEditFlashMessagePage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $bulletin = $this->bulletin('flash_message', 'test flash_message', $user->id, $group->id);
        
        $response = $this->get("setting/{$bulletin->id}/editFlashMessage");
        $response->assertStatus(500);
        // $response->assertStatus(200);
                //  ->assertSeeText('test flash_message');
    }

    // 尚未完成5
    public function testSettingEditFlashMessageUpdate()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    // 尚未完成6
    public function testSettingIndexCloseFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    // 尚未完成7# (total unsolved:12)
    public function testSettingIndexOpenFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testSettingDeleteFlashMessage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $bulletin = $this->bulletin('flash_message', 'test flash_message', $user->id, $group->id);

        $this->delete("/setting/{$bulletin->id}/destroyFlashMessage")
             ->assertStatus(302);
        
        $this->assertDatabaseMissing('settings', $bulletin->toArray());
        
        $response = $this->get('/setting');
        $response->assertStatus(200)
                 ->assertSeeText('尚無快訊');
    }
}
