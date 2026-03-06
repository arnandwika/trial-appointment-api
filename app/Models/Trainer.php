<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CourseClass;

class Trainer extends Model
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
        'name',
        'phone_number',
        'email',
        'gender',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function courseClasses()
    {
        return $this->belongsTo(CourseClass::class, 'class_id');
    }
}
