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
        $response->assertRedirect('/login');
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

    public function testDisplySettingEditCyclePage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $response = $this->get('/setting/editCycle');
        $response->assertStatus(200)
                 ->assertDontSeeText('群組ID');
    }

    public function testSettingEditCycleUpdate()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $setting = $this->setting($group->id);

        $params = [
            'cycle' => 10
        ];

        $this->put("/setting/updateCycle", $params)
             ->assertStatus(302);
        
        $this->assertDatabaseHas('settings', [
            'cycle' => 10
        ]);
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
        $response->assertStatus(200)
                 ->assertSeeText('返回');
    }

    public function testSettingEditFlashMessageUpdate()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $setting = $this->setting($group->id);
        $bulletin = $this->bulletin('flash_message', 'test flash_message', $user->id, $group->id);

        $params = [
            'flashMessage' => 'test flash_message updated'
        ];

        $this->put("setting/{$bulletin->id}/updateFlashMessage", $params)
             ->assertStatus(302);
        
        $this->assertDatabaseHas('bulletins', [
            'content' => 'test flash_message updated'
        ]);
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
