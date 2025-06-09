<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentModel extends Model
{
    use HasFactory;

    // اسم جدول ولي الأمر في قاعدة البيانات
    protected $table = 'parents';

    // اسم المفتاح الأساسي في جدول parents
    protected $primaryKey = 'parent_id';

    // الحقول التي يمكن تعبئتها (mass assignable) عبر الموديل
    protected $fillable = ['user_id'];

    /**
     * العلاقة: ولي الأمر ينتمي إلى مستخدم واحد فقط
     * كل ولي أمر مرتبط بحساب مستخدم في جدول users عبر user_id
     * belongsTo: هذه علاقة عكسية تشير إلى أن هذا الموديل تابع لموديل المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * العلاقة: ولي الأمر لديه عدة طلاب
     * كل ولي أمر يمكن أن يكون له عدة طلاب مرتبطين به عبر الحقل parent_id في جدول الطلاب
     * hasMany: علاقة واحد إلى متعدد (ولي الأمر -> طلاب)
     */
    public function students()
    {
        return $this->hasMany(studentmodel::class, 'parent_id', 'parent_id');
    }

    /**
     * العلاقة: كل ولي أمر له محفظة مالية واحدة فقط
     * تمثل المحفظة الرصيد المالي الخاص بولي الأمر
     * hasOne: علاقة واحد إلى واحد (ولي الأمر -> محفظة)
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'parent_id', 'parent_id');
    }

    /**
     * العلاقة: ولي الأمر لديه عدة منتجات ممنوعة
     * هذه المنتجات ممنوعة للطلاب التابعين له
     * hasMany: علاقة واحد إلى متعدد (ولي الأمر -> منتجات ممنوعة)
     */
    public function bannedProducts()
    {
        return $this->hasMany(BannedProduct::class, 'parent_id', 'parent_id');
    }
}
