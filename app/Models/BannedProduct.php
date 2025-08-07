<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedProduct extends Model
{
    protected $table = 'banned_products';
    protected $primaryKey = 'ban_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'student_id',
        'product_id',
        'created_at',
    ];


 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'student_id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
