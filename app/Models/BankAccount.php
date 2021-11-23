<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'card_number',
        'id_number',
        'exp_date',
    ];

    protected $casts = [
        'exp_date' => 'date',
    ];

    protected $guarded = [
        'id',
    ];

}
