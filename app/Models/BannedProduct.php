<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedProduct extends Model
{
    protected $table = 'banned_products';
    protected $primaryKey = 'ban_id';

    public $timestamps = false; // لأن created_at في الجدول فقط ولا يوجد updated_at

    protected $fillable = [
        'parent_id',
        'student_id',
        'product_id',
        'created_at',
    ];

    // علاقة المحظورات مع ولي الأمر
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'parent_id');
    }

    // علاقة المحظورات مع الطالب
    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'student_id');
    }

    // علاقة المحظورات مع المنتج (الطعام)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
