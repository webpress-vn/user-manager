<?php

namespace VCComponent\Laravel\User\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use VCComponent\Laravel\User\Repositories\UserRepository;

class SocialiteLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    protected $repository;
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;

    }
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($driver)
    {
        try {
            $user = Socialite::driver($driver)->user();
        } catch (\Exception $e) {
            return redirect()->route('login');
        }
        $existingUser = $this->repository->findByField('email', $user->getEmail())->first();
        if ($existingUser) {
            auth()->login($existingUser, true);
        } else {
            $newUser = $this->repository->create([
                'account_type' => $driver,
                'social_id' => $user->getId(),
                'first_name' => $user->getName(),
                'email' => $user->getEmail(),
                'avatar' => $user->getAvatar(),
                'verify_token' => str::random(32),
            ]);
            auth()->login($newUser, true);
        }
        return redirect('/');
    }
}
