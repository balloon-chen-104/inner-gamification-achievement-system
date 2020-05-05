<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    
    public function testRedirectWhenUserIsNotLogin()
    {
        $response = $this->get('/profile/1');
        $response->assertStatus(302);

        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function testRedirectWhenUserWithoutActiveGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get("/profile/{$user->id}");
        $response->assertStatus(200)
                 ->assertSeeText('基本資料');
    }

    public function testRedirectWhenUserIdDoesNotExistInTheGroup()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/profile/' . ($user->id + 1));
        $response->assertStatus(302);

        $response = $this->get('/login');
        $response->assertStatus(302);
    }

    public function testDisplyProfileIndexPageWhenUserViewOthersProfile()
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

        $response = $this->get("/profile/{$user2->id}");
        $response->assertStatus(200)
                 ->assertDontSeeText('編輯');
    }
    
    public function testDisplyProfileIndexPageWhenUserViewTheirOwnProfile()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get("/profile/{$user->id}");
        $response->assertStatus(200)
                 ->assertSeeText('編輯');
    }

    public function testProfileEditRedirectWhenUserViewOthersProfile()
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

        $response = $this->get("/profile/{$user2->id}/edit");
        $response->assertStatus(302);
    }

    public function testDisplyProfileEditPage()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get("/profile/{$user->id}/edit");
        $response->assertStatus(200)
                 ->assertSeeText('上傳照片');
    }

    public function testProfileEditSelfExpectationUpdate()
    {
        $user = $this->user();
        Auth::login($user, true);

        $params = [
            'self_expectation' => 'self_expectation update'
        ];

        $this->put("/profile/{$user->id}", $params)
             ->assertStatus(302);
        
        $this->assertDatabaseHas('users', [
            'self_expectation' => 'self_expectation update'
        ]);

        $response = $this->get("/profile/{$user->id}");
        $response->assertStatus(200)
                 ->assertSeeText('self_expectation update');
    }

    // 尚未完成1#
    public function testProfileEditPhotoUpdate()
    {
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');
        
        
        
        $user = $this->user();
        Auth::login($user, true);

        // $params = [
        //     'photo' => $file
        // ];

        // $this->put("/profile/{$user->id}", $params)
        //      ->assertStatus(302);
        
        $response = $this->json('PUT', "/profile/{$user->id}", [
            'photo' => $file,
        ]);
        
        $this->assertDatabaseHas('users', [
            'photo' => 'default-photo.jpg'
        ]);

        $response = $this->get("/profile/{$user->id}");
        $response->assertStatus(200);
    }

    public function testDisplyProfileIndexWithTasksInfo()
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
        
        $task->users()->attach($user->id, ['confirmed' => 1]);
        
        $response = $this->get("/profile/{$user->id}");

        $response->assertStatus(200)
                 ->assertSeeText($task->name);
    }

}
