<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;


    protected $table = 'order_items';


    protected $primaryKey = 'order_item_id';

      protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

     protected $casts = [
        'price' => 'decimal:2',
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
        // إجمالي سعر العنصر (الكمية × السعر)
    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

}
