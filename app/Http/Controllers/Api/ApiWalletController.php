<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class ApiWalletController extends Controller
{
 // عرض بيانات المحفظة (الرصيد + السقف)
    public function getWallet(Request $request)
    {
        $parent = $request->user()->parent; 

        $wallet = Wallet::where('parent_id', $parent->parent_id)->first();

        if (!$wallet) {
            return response()->json(['message' => 'لم يتم العثور على المحفظة'], 404);
        }

        return response()->json(['wallet' => $wallet]);
    }

    // تحديث أو إضافة سقف الشراء اليومي
    public function updateDailyLimit(Request $request)
    {
        $request->validate([
            'daily_limit' => 'required|numeric|min:0',
        ]);

        $parent = $request->user()->parent;

        $wallet = Wallet::updateOrCreate(
            ['parent_id' => $parent->parent_id],
            ['daily_limit' => $request->daily_limit]
        );

        return response()->json([
            'message' => 'تم تحديث سقف الشراء اليومي بنجاح',
            'wallet' => $wallet,
        ]);
    }}
