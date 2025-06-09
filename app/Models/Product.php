<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   

    // اسم المفتاح الأساسي
    protected $primaryKey = 'product_id';

    // الأعمدة القابلة للتعبئة
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_active',
    ];

    // العلاقة: المنتج ينتمي إلى صنف
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
