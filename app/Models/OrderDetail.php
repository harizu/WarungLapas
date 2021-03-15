<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'produk_id',
        'qty',
        'harga',
        'subtotal',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
