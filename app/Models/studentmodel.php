<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class studentmodel extends Model
{
    use HasFactory,SoftDeletes;


    protected $primaryKey = 'student_id';

        protected $table = 'students';

       protected $fillable = [
        'user_id',
        'full_name',
        'father_name',
        'class',
         'image_path',
         'pin_code',
          'daily_limit',
    ];


    public function user()
    {
      
        return $this->belongsTo(User::class, 'user_id');
    }
public function bannedProducts()
    {
        return $this->hasMany(BannedProduct::class, 'student_id', 'student_id');
    }

public function orders()
{
    return $this->hasMany(Order::class, 'student_id', 'student_id');
}

}
