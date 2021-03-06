<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'birthday',
        'telephone',
        'username',
        'email',
        'password',
        'gender',
        'address',
        'status',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // user has many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
