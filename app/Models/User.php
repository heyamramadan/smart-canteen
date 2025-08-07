<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
   protected $fillable = [
    'username',
    'email',
    'password',
   'full_name',
    'role',
    'phone_number',
     'profile_image_url',
];



public function students()
{
    // يربط هذا المستخدم بجدول الطلاب عبر user_id
    return $this->hasMany(studentmodel::class, 'user_id', 'id');
}

/**
 * العلاقة: المستخدم (ولي الأمر) لديه محفظة واحدة
 */
public function wallet()
{
    return $this->hasOne(Wallet::class, 'user_id', 'id');
}

/**
 * العلاقة: المستخدم (ولي الأمر) يمكنه حظر عدة منتجات
 */
public function bannedProducts()
{
    return $this->hasMany(BannedProduct::class, 'user_id', 'id');
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
