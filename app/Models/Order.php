<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_no',
        'user_id',
        'warga_binaan_id',
        'total',
        'biaya_layanan',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getTotalPembayaranAttribute()
    {
        return ($this->total + $this->biaya_layanan);
    }
}
