<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $primaryKey = 'category_id'; // إذا لم يكن id هو المفتاح الأساسي

    protected $fillable = [
        'name',
        'description',
    ];
    public function products()
{
    return $this->hasMany(Product::class, 'category_id', 'category_id');
}

}
