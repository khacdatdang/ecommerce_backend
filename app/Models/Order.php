<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Order belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
