<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $primaryKey = 'product_id';


    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
     'quantity',
        'image',
        'is_active',
         'expiry_date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }



    public function bannedProducts()
    {
        return $this->hasMany(BannedProduct::class, 'product_id', 'product_id');
    }
    
    public function orderItems()
{
    return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
}

}
