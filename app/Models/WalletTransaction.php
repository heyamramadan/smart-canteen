<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'wallet_transactions'; // اسم الجدول
    protected $primaryKey = 'transaction_id'; // المفتاح الأساسي

    public $timestamps = false; // لا يحتوي على updated_at

    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'reference',
        'created_at',
    ];

    // علاقة: المعاملة تنتمي إلى محفظة
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'wallet_id');
    }
}
