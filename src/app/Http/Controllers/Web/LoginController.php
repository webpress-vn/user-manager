<?php

namespace VCComponent\Laravel\User\Http\Controllers\Web;

use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use VCComponent\Laravel\User\Entities\User;

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
        $view = config('user.test_mode') === true ? view('userTest::login') : view('auth.login');
        return $view;
    }

    public function username()
    {
        return 'username';
    }

    protected function credentials($request)
    {

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return [
                'email'    => $request->get('username'),
                'password' => $request->get('password'),
            ];
        }
        return [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];
    }
}
