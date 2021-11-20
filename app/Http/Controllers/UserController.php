<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //
    function register(Request $request)
    {
        // check email or username is exist
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->username)
            ->first();
        if ($user) {
            return ['success' => false, 'message' => 'Email or Username is exist'];
        }
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return ['success' => true, 'user' => $user];
    }

    function login(Request $request)
    {
        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return ['success' => true, 'user' => $user];
            } else {
                return ['success' => false, 'message' => 'Wrong password.'];
            }
        } else {
            return ['success' => false, 'message' => 'Wrong Username or Email.'];
        }
    }

    // set new info for user
    function set_info(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $check = User::where('email', $request->email)
            ->orWhere('telephone', $request->telephone)
            ->first();

        if ($check) {
            if ($check->id != $user->id) {
                return ['success' => false, 'message' => 'Email or Phone is exist'];
            }
        }

        $user->update([
            'name' => $request->name,
            'birthday' => $request->birthday,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);
        return ['success' => true, 'user' => $user];
    }

    // set new password for user
    function set_password(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if (Hash::check($request->password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->newPassword),
            ]);
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'message' => 'Wrong password.'];
        }
    }
}
