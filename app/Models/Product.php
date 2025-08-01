<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // اسم المفتاح الأساسي في جدول المنتجات
    protected $primaryKey = 'product_id';

    // الحقول التي يمكن تعبئتها (mass assignable) عبر الموديل
    protected $fillable = [
        'category_id',  // رقم الصنف الذي ينتمي إليه المنتج
        'name',         // اسم المنتج
        'description',  // وصف المنتج
        'price',        // سعر المنتج
     'quantity',// كمية منتج
        'image',        // رابط أو اسم صورة المنتج (اختياري)
        'is_active',    // حالة تفعيل المنتج (1 = مفعل، 0 = غير مفعل)
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }



    public function bannedProducts()
    {
        return $this->hasMany(BannedProduct::class, 'product_id', 'product_id');
    }
    //كل منتج يمكن أن يظهر في عدة طلبات مختلفة.
    public function orderItems()
{
    return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
}

}
