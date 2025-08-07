<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes;

    protected $table = 'wallets';

    protected $primaryKey = 'wallet_id';

    protected $fillable = [
        'user_id',
        'balance',

    ];

   
    public function user()
    {
          return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
