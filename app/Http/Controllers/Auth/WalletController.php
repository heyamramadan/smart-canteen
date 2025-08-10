<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function index()
    {
        $parents = User::where('role', 'ولي أمر')
                       ->with('wallet')
                       ->get();

        return view('wallet', ['users' => $parents]);


    }


    public function charge(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);


        $wallet = Wallet::firstOrCreate(
            ['user_id' => $request->user_id],
               ['balance' => 0]
        );

        $wallet->balance += $request->amount;


        $wallet->save();
        
        WalletTransaction::create([
            'wallet_id' => $wallet->wallet_id,
            'amount' => $request->amount,
            'type' => 'إيداع',
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'تم شحن الرصيد بنجاح']);
    }
}
