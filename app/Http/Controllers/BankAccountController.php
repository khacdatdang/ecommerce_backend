<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\BankAccount;
use Illuminate\Http\Request;



class BankAccountController extends Controller
{
    //
    function set_payment(Request $request)
    {
        $messages = [
            'name.min'   =>   "Name is too short (minimum is 6 characters)",
            'name.max'   =>   "Name is too long (maximum is 30 characters)",
        ];

        $rules = [
            'card_number' => 'required|digits:16',
            'name'        => 'required|min:5|max:40',
            'id_number'   => 'required|digits_between:10,11',
            'exp_date'    => 'required|date_format:Y-m',
        ];

        //validation form
        $validate =  Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return ['success' => 0, 'message' => $validate->errors()->first()];
        } else {
            $account = BankAccount::where('id', $request->id)->first();

            if ($account) {
                $account->update([
                    'name' => $request->name,
                    'card_number' => $request->card_number,
                    'id_number' => $request->id_number,
                    'exp_date' => $request->exp_date,
                ]);
            } else {
                $account = BankAccount::create([
                    'id' => $request->id,
                    'name' => $request->name,
                    'card_number' => $request->card_number,
                    'id_number' => $request->id_number,
                    'exp_date' => $request->exp_date,
                ]);
            }
            return ['success' => true, 'account' => $account];
        }
    }
}
