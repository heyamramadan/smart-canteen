<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;


    protected $table = 'orders';


    protected $primaryKey = 'order_id';

   
    protected $fillable = [
        'student_id',
        'employee_id',
        'total_amount',

    ];

    // علاقة الطلب ينتمي إلى طالب (Student)
public function student()
{
    return $this->belongsTo(StudentModel::class, 'student_id', 'student_id')->withTrashed();
}

    // علاقة الطلب ينتمي إلى موظف (User)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
    //كل طلب يمكن أن يحتوي على عدة عناصر (منتجات).
    public function orderItems()
{
    return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
}

}
