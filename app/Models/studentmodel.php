<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class studentmodel extends Model
{
    use HasFactory,SoftDeletes;


    // المفتاح الأساسي للجدول (رقم تعريف الطالب)
    protected $primaryKey = 'student_id';

    // اسم جدول الطلاب في قاعدة البيانات
    protected $table = 'students';

    // الحقول القابلة للتعبئة من خلال الموديل
    protected $fillable = [
        'user_id', // تم تغيير parent_id إلى user_id
        'full_name',   // الاسم الكامل للطالب
        'father_name', // اسم الأب (يمكن استخدامه للتمييز أو البيانات التعريفية)
        'class',       // الصف الدراسي أو المرحلة التعليمية للطالب
         'image_path',
         'pin_code',
    ];

    /**
     * العلاقة: الطالب ينتمي إلى مستخدم واحد (ولي أمره)
     */
    public function user()
    {
        // نربط عبر user_id في جدول students
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
