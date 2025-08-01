<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    // اسم الجدول في قاعدة البيانات
    protected $table = 'order_items';

    // اسم المفتاح الأساسي
    protected $primaryKey = 'order_item_id';

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * العلاقة: العنصر ينتمي إلى طلب
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * العلاقة: العنصر ينتمي إلى منتج
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
