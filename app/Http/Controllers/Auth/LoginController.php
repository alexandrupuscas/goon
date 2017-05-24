<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use App\User;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(
            ['logout', 'getFacebookCallback', 'redirectToFacebook']
        );
    }

    public function redirectToFacebook()
    {
        return Socialite::with('facebook')->redirect();
    }

    public function getFacebookCallback()
    {
        $data = Socialite::with('facebook')->user();
        $user = User::where('email', $data->email)->first();

        if(!is_null($user)) {
            $user->name = $data->user['name'];
            $user->facebook_id = $data->id;
        } else {
            $user = User::where('facebook_id', $data->id)->first();

            if(is_null($user)){
                // Create a new user
                $user = new User();
                $user->name = $data->user['name'];
                $user->email = $data->email;
                $user->facebook_id = $data->id;
                $user->password = bcrypt('secret');
            }
        }

        $user->save();

        Auth::login($user);

        return redirect('/home')->with('success', 'Successfully logged in!');
    }

    public function logout(){
        Auth::logout();
        return redirect('/about')->with('success', 'You have logged out');
    }

}
