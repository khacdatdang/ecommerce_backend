<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Customer;

class UserController extends Controller
{
    //
    function find_user(Request $request)
    {
        $user = User::where('email', $request->email)
            ->where('username', $request->username)
            ->where('telephone', $request->telephone)
            ->first();

        if ($user) {
            return ['success' => true, 'user_id' => $user->id];
        } else {
            return ['success' => false, 'message' => 'User not found'];
        }
    }

    function register(Request $request)
    {
        $messages = [
            'username.min'             =>   "Username is too short (minimum is 6 characters)",
            'username.max'             =>   "Username is too long (maximum is 30 characters)",
            'email.required'           =>   "The :attribute field is required",
            'email.email'              =>   "The :attribute :input format should be example@example.com/.in/.edu/.org....",
            'email.unique'             =>   "The :attribute :input is taken. Please use another email address",
            'password.min'             =>   "Password is too short (minimum is 8 characters)",
            'password.max'             =>   "Password is too long (maximum is 30 characters)",
            'confirmPassword.min'     =>   "Confirm password is too short (minimum is 8 characters)",
            'confirmPassword.max'     =>   "Confirm password is too long (maximum is 30 characters)",
            'confirmPassword.same'    =>   "Password and Confirm password fields must match exactly",
        ];

        $rules = [
            'username'         => 'required|min:6|max:30',
            'email'            => 'required|email|unique:users',
            'password'         => 'required|min:8|max:30',
            'confirmPassword' => 'required|min:8|max:30|same:password',
        ];

        //validation form
        $validate =  Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return ['success' => false, 'message' => $validate->errors()->first()];
        } else {
            // check email or username is exist
            $user = User::where('email', $request->email)
                ->orWhere('username', $request->username)
                ->orWhere('telephone', $request->telephone)
                ->first();

            if ($user) {
                return ['success' => false, 'message' => 'Email, Username or Phone is exist'];
            }

            // check customer already exists in database
            // if not create new customer then create user
            // if yes, create user
            $customer = Customer::where('telephone', $request->telephone)->first();

            if ($customer) {
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'telephone' => $request->telephone,
                    'customer_id' => $customer->id,
                ]);
                // update customer id
                $customer = $customer->update([
                    'user_id' => $user->id
                ]);
            } else {
                $customer = Customer::create([
                    'telephone' => $request->telephone,
                ]);
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'telephone' => $request->telephone,
                    'customer_id' => $customer->id,
                ]);
                $customer = $customer->update([
                    'user_id' => $user->id
                ]);
            }

            return ['success' => true, 'user' => $user];
        }
    }

    function validate_user(Request $request)
    {
        $messages = [
            'login.min'     =>   "Username is too short (minimum is 6 characters)",
            'login.max'     =>   "Username is too long (maximum is 30 characters)",
            'password.min'  =>   "Password is too short (minimum is 8 characters)",
            'password.max'  =>   "Password is too long (maximum is 30 characters)",
        ];

        $rules = [
            'login'      => 'required|min:6|max:30',
            'password'   => 'required|min:8|max:30',
        ];

        //validation form
        $validate =  Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return ['success' => 0, 'message' => $validate->errors()->first()];
        } else {
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
                return ['success' => false, 'message' => 'Wrong Username or Username.'];
            }
        }
    }

    // set new info for user
    function set_info(Request $request)
    {
        $messages = [
            'name.min'                 =>   "Name is too short (minimum is 6 characters)",
            'name.max'                 =>   "Name is too long (maximum is 30 characters)",
            'email.required'           =>   "The :attribute field is required",
            'email.email'              =>   "The :attribute :input format should be example@example.com/.in/.edu/.org....",
            'email.unique'             =>   "The :attribute :input is taken. Please use another email address",
            'address.min'              =>   "Address is too short (minimum is 15 characters)",
            'address.max'              =>   "Address is too long (maximum is 100 characters)",
            'telephone.unique'         =>   "The :attribute :input is taken. Please use another telephone",
        ];

        $rules = [
            'name'             => 'required|min:5|max:40',
            'email'            => ['required', 'email', 'string', Rule::unique('users')->ignore($request->id)],
            'telephone'        => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'digits_between:10,12', Rule::unique('users')->ignore($request->id)],
            'gender'           => 'nullable',
            'address'          => 'nullable|min:15|max:100',
            'birthday'         => 'nullable|date|date_format:Y-m-d',
        ];

        //validation form
        $validate =  Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return ['success' => 0, 'message' => $validate->errors()->first()];
        } else {
            $user = User::where('id', $request->id)->first();
            if (!is_null($request->telephone)) {
                $check = User::where('email', $request->email)
                    ->orWhere('telephone', $request->telephone)
                    ->first();
            } else {
                $check = User::where('email', $request->email)->first();
            }

            if ($check) {
                if ($check->id != $user->id) {
                    return ['success' => false, 'message' => 'Email or Phone is exist' . ' check:' . $check->email . ',user: ' . $user->id . $request->email . $request->telephone];
                }
            }

            $user->update([
                'name' => $request->name,
                'birthday' => $request->birthday,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'gender' => $request->gender,
                'address' => $request->address,
            ]);
            return ['success' => true, 'user' => $user];
        }
    }

    // set new password for user
    function set_password(Request $request)
    {
        $messages = [
            'password.min'             =>   "Password is too short (minimum is 8 characters)",
            'password.max'             =>   "Password is too long (maximum is 30 characters)",
            'newPassword.min'          =>   "New Password is too short (minimum is 8 characters)",
            'newPassword.max'          =>   "New Password is too long (maximum is 30 characters)",
            'confirmPassword.min'      =>   "Confirm password is too short (minimum is 8 characters)",
            'confirmPassword.max'      =>   "Confirm password is too long (maximum is 30 characters)",
            'confirmPassword.same'     =>   "Password and Confirm password fields must match exactly",
        ];

        $rules = [
            'password'         => 'required|min:8|max:30',
            'newPassword'      => 'required|min:8|max:30',
            'confirmPassword'  => 'required|min:8|max:30|same:newPassword',
        ];

        //validation form
        $validate =  Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return ['success' => 0, 'message' => $validate->errors()->first()];
        } else {
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

    function reset_password(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->newPassword),
            ]);
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'message' => 'Something wrong!'];
        }
    }

    function get_orders($id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            $customer = $user->customer;
            $orders = $customer->orders;
            return ['success' => true, 'orders' => $orders];
        } else {
            return ['success' => false, 'message' => 'Something wrong!'];
        }
    }
}
