<?php

namespace VCComponent\Laravel\User\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class ResetPasswordController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetResponse($response)
    {
        $token = Auth::user()->getToken();
        return $this->response->array(['token' => $token]);
    }

    public function sendResetFailedResponse($response)
    {
        throw new UnauthorizedHttpException('', "Token doesn't match or expired", null, 1003);
    }

    protected function resetPassword($user, $password)
    {
        $user->password = $password;

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    protected function rules()
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
