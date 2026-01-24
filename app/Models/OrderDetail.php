<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'package_id',
        'class_id',
        'package_name',
        'class_name',
        'total_quota',
        'remaining_quota',
        'used_quota',
    ];

    protected $casts = [
        'total_quota'     => 'integer',
        'remaining_quota' => 'integer',
        'used_quota'      => 'integer',
    ];

    /**
     * Relasi ke Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
