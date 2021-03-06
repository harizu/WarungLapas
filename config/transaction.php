<?php

return [
    'rekening' => [
        'bank' => env('BANK_REKENING_PEMBAYARAN', null),
        'no' => env('NO_REKENING_PEMBAYARAN', null),
        'atas_nama' => env('NAMA_REKENING_PEMBAYARAN', null),
    ],

    'running_number_pad' => 5,

    'code' => [
        'order' => 'ORD',
    ],
];
