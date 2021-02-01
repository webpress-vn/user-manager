<?php

namespace VCComponent\Laravel\User\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Repositories\UserRepository;
use Illuminate\Routing\Controller;

class ResendVerifyController extends Controller
{

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->viewError  = config('user.test_mode') === true ? view('userTest::errow-verify') : view('auth.errow-verify');

    }
    
    public function view(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return $this->viewError;
        }

        if (!Auth::check()) {
            Auth::login($user, true);
        }

        if ($user->email_verified === 0) {
            if ($user->verify_token === $request->get('token')) {
                $user = $this->repository->verifyEmail($user);
            } else {
                return $this->viewError;
            }
        } else {
            return $this->viewError;
        }

        return view('auth.verify');
    }

    public function notMe(Request $request)
    {
        $user = $this->repository->find($request->id);

        if (!$user) {
            return $this->viewError;
        }
        if ($user->email_verified === 0) {

            if ($user->verify_token === $request->get('token')) {

                $user->status            = 2;
                $user->email_verified_at = Carbon::now();
                $user->email_verified    = 2;

                $user->save();
            } else {
                return $this->viewError;
            }
        } else {
            return $this->viewError;
        }

        return $this->viewError;

    }
}
