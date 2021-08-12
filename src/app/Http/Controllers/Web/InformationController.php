<?php

namespace VCComponent\Laravel\User\Http\Controllers\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use VCComponent\Laravel\User\Entities\User;

class InformationController extends Controller
{
    public function index()
    {
        $user   = User::whereId(Auth::user()->id)->with('sex')->first();
        $gender = '';

        if ($user->gender !== null) {
            $gender = $user->sex->value;
        }

        $date = [
            0 => "", 1 => "", 2 => "",
        ];

        $date_of_birth = '';

        if ($user->birth !== null) {
            $birthday      = $user->birth;
            $date          = explode('-', $birthday);
            $date_of_birth = Carbon::parse($birthday)->calendar();
        }

        $view = config('user.test_mode') === true ? 'userTest::account' : 'auth.account';
        return view($view, compact('gender', 'date', 'date_of_birth'));
    }

    public function editInfo(Request $request)
    {

        $birth = null;
        if ($request['years'] !== null && $request['moths'] !== null && $request['days'] !== null) {
            $birth = $request['years'] . "-" . $request['moths'] . "-" . $request['days'];
        }

        $data = [
            "first_name"   => $request['first_name'],
            "last_name"    => $request['last_name'],
            "gender"       => $request['gender'],
            "email"        => $request['email'],
            "phone_number" => $request['phone_number'],
            "address"      => $request['address'],
            "birth"        => $birth,
        ];

        $user = User::find($request['auth_id']);

        $validator = Validator::make($data, [
            'email' => ['unique:users,email,' . $request['auth_id'], 'email'],
        ]);

        if ($validator->fails()):
            return redirect()->back()->withErrors($validator)->withInput();
        endif;

        $user->update($data);

        return redirect()->back()->with('messages', ('Thay đổi thông tin cá nhân thành công !'));
    }
}
