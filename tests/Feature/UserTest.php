<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Auth;
use Socialite;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testDisplayRegisterPage()
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
                 ->assertSeeText('確認密碼');
    }

    public function testUserRegisterAndLoginAutomatically()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/register');

        $response->assertStatus(302);
        $this->assertEquals(Auth::user()->id, $user->id);
    }

    public function testDisplayLoginPage()
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
                 ->assertSeeText('微軟登入');
    }

    public function testUserNormallyLogin()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $response = $this->get('/');

        $response->assertStatus(200)
                 ->assertSeeText('群組');
                 
        $this->assertEquals(Auth::user()->id, $user->id);
    }

    public function testUserLoginViaAzure()
    {
        // 尚未完成
        $response = $this->get('/login/azure');

        $response->assertStatus(302);
    }

    public function testUserLogout()
    {
        $user = $this->user();
        Auth::login($user, true);
        
        $this->post('/logout')
             ->assertStatus(302);
    }

    private function user(): User
    {
        return User::create([
            'name' => 'name',
            'email' => 'email@test.com',
            'password' => Hash::make('password'),
            'api_token' => Str::random(80),
        ]);
    }
}
