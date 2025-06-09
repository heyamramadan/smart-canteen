<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $primaryKey = 'wallet_id';

    protected $fillable = [
        'parent_id',
        'balance',
    ];

    // العلاقة: المحفظة تنتمي إلى ولي الأمر
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'parent_id');
    }
}
