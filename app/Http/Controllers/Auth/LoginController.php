<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    public function showLoginForm()
    {
        //we want to go the url the user was before interrupted by login form
        session()->put('previousUrl', url()->previous());

        return view('auth.login');
    }

    public function redirectTo()
    {
        // dd(session()->get('previousUrl'));
        //the above will give us for instance:
        //"http://localhost:8000/shop?category=mobile-phones"
        // but we want anything after the 8000
        // we use str_replace() to replace that part with an empty string
        //now, if there is no session, go to the home page ('/')
        return str_replace(url('/'), '', session()->get('previousUrl', '/'));
    }
}
