<?php

namespace App\Services;

use App\Models\TransactionCode;
use Carbon\Carbon;

class TransactionCodeService
{
    public function generateCode(string $transaction_type, string $transaction_date)
    {
        if (!$transaction_code = config('transaction.code.' . $transaction_type)) {
            throw new Exception('transaction type: ' . $transaction_type . ' not configured.');
        }

        // get record for next running number to use for transaction type specified
        $current_code = TransactionCode::lockForUpdate()->where([
            'transaction_type' => $transaction_type,
            'transaction_date' => $transaction_date,
        ])->firstOr(function () use ($transaction_type, $transaction_date) {
            // create initial record if not exists
            return TransactionCode::create([
                'transaction_type' => $transaction_type,
                'transaction_date' => $transaction_date,
            ])->refresh(); // refresh to get default next number from db layer
        });

        $running_number = $current_code->next_number;

        // immediately increment next number after getting running number
        $current_code->increment('next_number');
        $date_code = Carbon::parse($transaction_date)->format('ymd');
        $running_number = str_pad($running_number, config('transaction.running_number_pad'), "0", STR_PAD_LEFT);

        $code = "{$transaction_code}{$date_code}{$running_number}";

        return $code;
    }
}
