<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;

class BulletinTest extends TestCase
{
    use RefreshDatabase;
    
    public function testBulletinIndexRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/bulletin');
        $response->assertRedirect('/login');
    }

    public function testBulletinIndexRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/bulletin');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testDisplyBulletinIndexWithNoDatas()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);

        $user->active_group = $group->id;
        $user->save();
        
        $response = $this->get('/bulletin');

        $response->assertStatus(200)
                 ->assertSeeText('尚無公告')
                 ->assertSeeText('目前沒有任何任務');
    }

    public function testDisplyBulletinIndexWithFlashMessage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        
        $user->active_group = $group->id;
        $user->save();

        $bulletin = $this->bulletin('flash_message', 'test content', $user->id, $group->id);

        $response = $this->get('/bulletin');

        $response->assertStatus(200)
                 ->assertSeeText('test content');
    }

    // 尚未完成
    public function testDisplyBulletinIndexCloseFlashMessage()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function testDisplyBulletinIndexWithAnnouncement()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        
        $user->active_group = $group->id;
        $user->save();

        $bulletin = $this->bulletin('announcement', 'test announcement', $user->id, $group->id);

        $response = $this->get('/bulletin');

        $response->assertStatus(200)
                 ->assertSeeText('test announcement');
    }
    
    public function testBulletinCreateRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/bulletin/create');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testBulletinCreateRedirectWhenUserIsNotAdmin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id + 1);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('/bulletin/create');

        $response->assertStatus(302);
    }
    
    public function testDisplyBulletinCreatePage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
        
        $response = $this->get('/bulletin/create');

        $response->assertStatus(200)
                 ->assertSeeText('新增公告');
    }

    public function testBulletinCreateStore()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();
        
        $params = [
            'content' => 'test announcement create'
        ];

        $this->post('/bulletin', $params)
             ->assertStatus(302);
        
        $this->assertDatabaseHas('bulletins', [
            'type' => 'announcement',
            'content' => 'test announcement create',
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $response = $this->get("/bulletin");

        $response->assertStatus(200)
                 ->assertSeeText('test announcement create');
    }

    public function testBulletinEditRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);

        $response = $this->get('/bulletin/1/edit');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200)
                 ->assertSeeText('建立群組');
    }

    public function testBulletinEditRedirectWhenUserIsNotAdmin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id + 1);
        $group->users()->attach($user->id, ['authority' => 0]);

        $user->active_group = $group->id;
        $user->save();

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('/bulletin/{$bulletin->id}/edit');

        $response->assertStatus(302);
    }

    public function testDisplyBulletinEditPage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
        
        $bulletin = $this->bulletin('announcement', 'test announcement', $user->id, $group->id);
        
        $this->assertDatabaseHas('bulletins', [
            'type' => 'announcement',
            'content' => 'test announcement',
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $response = $this->get("/bulletin/{$bulletin->id}/edit");

        $response->assertStatus(200)
                 ->assertSeeText('編輯公告');
    }

    public function testBulletinEditUpdate()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();
        
        $bulletin = $this->bulletin('announcement', 'test announcement', $user->id, $group->id);

        $params = [
            'content' => 'test announcement update'
        ];

        $this->put("/bulletin/{$bulletin->id}", $params)
             ->assertStatus(302);
        
        $this->assertDatabaseMissing('bulletins', $bulletin->toArray());

        $this->assertDatabaseHas('bulletins', [
            'type' => 'announcement',
            'content' => 'test announcement update',
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $response = $this->get("/bulletin");

        $response->assertStatus(200)
                 ->assertSeeText('test announcement update');
    }

    public function testBulletinDestroy()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 1]);

        $user->active_group = $group->id;
        $user->save();
        
        $bulletin = $this->bulletin('announcement', 'test announcement', $user->id, $group->id);
        
        $this->assertDatabaseHas('bulletins', [
            'type' => 'announcement',
            'content' => 'test announcement',
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);

        $this->delete("/bulletin/{$bulletin->id}")
             ->assertStatus(302);
        
        $this->assertDatabaseMissing('bulletins', $bulletin->toArray());
    }

    public function testDisplyBulletinIndexWithTasks()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $group = $this->group($user->id);
        $group->users()->attach($user->id, ['authority' => 0]);
        $category = $this->category($group->id);
        $task = $this->task($category->id);

        $task->confirmed = 1;
        $task->save();

        $user->active_group = $group->id;
        $user->save();
        
        $response = $this->get('/bulletin');

        $response->assertStatus(200)
                 ->assertSeeText('尚無公告')
                 ->assertSeeText($task->name);
    }
}
