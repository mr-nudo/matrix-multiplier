<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function signup(Request $request)
    {
        $this->validate($request, [
            'account_name' => 'required|string',
            'email_address' => 'required|email|unique:users'
        ]);

        //generate username and password for basic auth
        $username = UtilityHelper::generateRandomCharacters();
        $password = UtilityHelper::generateRandomCharacters(4);

        //create new user account
        $user = new User();
        $user->account_name = $request->account_name;
        $user->email_address = $request->email_address;
        $user->username = $username;
        $user->password = $password;
        $user->save();

        //send mail notification

        //return response
        return response()->json(['status' => 'success', 'data' => ['username' => $username, 'password' => $password]], 200);
    }
}
