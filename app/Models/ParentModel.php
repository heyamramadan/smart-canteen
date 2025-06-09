<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $primaryKey = 'parent_id';

    protected $fillable = ['user_id'];

    // علاقة: ولي الأمر ينتمي إلى مستخدم
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ✅ علاقة: ولي الأمر لديه عدة طلاب
    public function students()
    {
        return $this->hasMany(studentmodel::class, 'parent_id', 'parent_id');
    }
    //علاقة مع محافظة
    public function wallets()
{
    return $this->hasMany(Wallet::class, 'parent_id', 'parent_id');
}

}
