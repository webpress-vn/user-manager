<?php

namespace VCComponent\Laravel\User\Http\Controllers\Web;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use VCComponent\Laravel\User\Entities\User;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if ($data['email'] !== null) {
            $validator = Validator::make($data, [
                'email' => 'email|max:255|unique:users',
            ]);
        }

        if ($data['username'] !== null) {
            $validator = Validator::make($data, [
                'username' => ['unique:users'],
            ]);
        }

        $validator = Validator::make($data, [
            'phone_number' => ['required', 'string', 'regex:/^\d*$/'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    protected function create(array $data)
    {
        return User::create([
            'username'     => $data['username'],
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'],
            'address'      => $data['address'],
            'password'     => $data['password'],
            'verify_token' => str::random(32),
        ]);
    }

}
