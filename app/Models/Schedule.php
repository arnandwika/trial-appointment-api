<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CourseClass;
use App\Models\Trainer;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

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

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'class_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }
}
