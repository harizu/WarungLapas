<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCode extends Model
{
    protected $fillable = [
        'transaction_type',
        'transaction_date',
    ];
}
