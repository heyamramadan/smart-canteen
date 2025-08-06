<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'wallet_transactions';
    protected $primaryKey = 'transaction_id';

    public $timestamps = false; 

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
          return $this->belongsTo(Wallet::class, 'wallet_id', 'wallet_id')->withTrashed();
    }
}
