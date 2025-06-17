<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class studentmodel extends Model
{
    use HasFactory;
    

    // المفتاح الأساسي للجدول (رقم تعريف الطالب)
    protected $primaryKey = 'student_id';

    // اسم جدول الطلاب في قاعدة البيانات
    protected $table = 'students';

    // الحقول القابلة للتعبئة من خلال الموديل
    protected $fillable = [
        'parent_id',   // معرف ولي الأمر المرتبط بهذا الطالب
        'full_name',   // الاسم الكامل للطالب
        'father_name', // اسم الأب (يمكن استخدامه للتمييز أو البيانات التعريفية)
        'class',       // الصف الدراسي أو المرحلة التعليمية للطالب
    ];

    /**
     * علاقة: الطالب ينتمي إلى ولي أمر واحد
     * belongsTo: علاقة عكسية تربط الطالب بولي أمره عبر مفتاح parent_id
     */
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'parent_id');
    }

    /**
     * علاقة: الطالب يمكن أن يكون لديه عدة أطعمة ممنوعة
     * hasMany: علاقة واحد إلى متعدد بين الطالب وجدول المنتجات الممنوعة له
     */
    public function bannedProducts()
    {
        return $this->hasMany(BannedProduct::class, 'student_id', 'student_id');
    }
    /**
 * علاقة: الطالب لديه عدة فواتير
 * hasMany: تربط الطالب بكل الطلبات المرتبطة به عبر student_id
 */
public function orders()
{
    return $this->hasMany(Order::class, 'student_id', 'student_id');
}

}
