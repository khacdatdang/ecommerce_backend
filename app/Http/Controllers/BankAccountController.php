<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    //
    function set_payment(Request $request)
    {
        $account = BankAccount::where('id', $request->id)->first();

        if ($account) {
            $account->update([
                'name' => $request->name,
                'account_number' => $request->card_number,
                'bank_name' => $request->bank_name,
            ]);
        } else {
            $account = BankAccount::create([
                'id' => $request->id,
                'name' => $request->name,
                'account_number' => $request->card_number,
                'bank_name' => $request->bank_name,
            ]);
        }
        return ['success' => true, 'account' => $account];
    }
}
