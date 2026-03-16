<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CourseClass;


class Package extends Model
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
        'title',
        'description',
        'quota',
        'price',
        'is_active',
        'valid_days'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'class_id');
    }
}
