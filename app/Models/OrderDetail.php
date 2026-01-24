<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    public $timestamps = false; // ⚠️ penting kalau tabel gak punya timestamps

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

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
