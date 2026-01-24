<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', true);
        });
    }

    protected $fillable = [
        'class_id',
        'trainer_id',
        'datetime_schedule',
        'used_capacity',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
