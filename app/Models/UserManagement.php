<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserManagement extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user_management';

    protected $fillable = [
        'role',
        'name',
        'phone_number',
        'email',
        'gender',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
