<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ],[
            $this->username().'|required' => 'Tài khoản đăng nhập không chính xác',
            'password|required' => 'Mật khẩu không chính xác',
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $request->merge([
            'status' => User::ACTIVATE_STATUS,
        ]);

        if (is_numeric($request->get('email'))) {
            return [
                'mobile' => $request->get('email'),
                'password' => $request->get('password'),
                'status' => $request->get('status'),
            ];
        }
        return $request->only($this->username(), 'password', 'status');

    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/login');
    }

}
