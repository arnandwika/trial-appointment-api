<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'class_type',
        '',
        '',
        ''
    ];
}
