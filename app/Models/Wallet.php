<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $primaryKey = 'wallet_id';

    protected $fillable = [
        'user_id', // تم تغيير parent_id إلى user_id
        'balance',
         'daily_limit',
    ];

    // العلاقة: المحفظة تنتمي إلى ولي الأمر
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
