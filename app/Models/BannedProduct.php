<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedProduct extends Model
{
    protected $table = 'banned_products';
    protected $primaryKey = 'ban_id';

    public $timestamps = false; // لأن created_at في الجدول فقط ولا يوجد updated_at

    protected $fillable = [
        'user_id', // تم تغيير parent_id إلى user_id
        'student_id',
        'product_id',
        'created_at',
    ];

  
     /**
     * العلاقة: الحظر تم بواسطة مستخدم معين (ولي الأمر)
     */
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
