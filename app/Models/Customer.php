<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'telephone',
        'user_id'
    ];

    // Customer has one user
    public function user()
    {
        return $this->hasOne(User::class);
    }

    // Customer has many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
