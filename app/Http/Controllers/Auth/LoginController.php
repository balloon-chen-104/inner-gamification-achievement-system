<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use App\User;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the third party authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the third party.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $thirdPartyUser = Socialite::driver($provider)->user();

        // dd($thirdPartyUser);
        // dd($thirdPartyUser->user['department']);
        // dd($thirdPartyUser->user['jobTitle']);

        // Check if user already exist
        $user = User::where('email', $thirdPartyUser->getEmail())->first();
        $department = is_null($thirdPartyUser->user['department']) ? '*' : $thirdPartyUser->user['department'];
        $job_title = is_null($thirdPartyUser->user['jobTitle']) ? '*' : $thirdPartyUser->user['jobTitle'];

        if(!$user){
            // Add user to database
            $name = is_null($thirdPartyUser->getNickname()) ? $thirdPartyUser->getName() : $thirdPartyUser->getNickname();
            $user = User::create([
                'name' => $name,
                'email' => $thirdPartyUser->getEmail(),
                'department' => $department,
                'job_title' => $job_title,
                'api_token' => Str::random(80),
            ]);
        }

        // Login the user
        Auth::login($user, true);

        return redirect($this->redirectTo);
    }
}
