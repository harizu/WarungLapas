<?php

namespace App\Http\Requests;

use App\Models\Penjualan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePenjualanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('penjualan_create');
    }

    public function rules()
    {
        return [
            'trx_no'      => [
                'string',
                'required',
                'unique:penjualans',
            ],
            'product_id'  => [
                'required',
                'integer',
            ],
            'qty'         => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'total_price' => [
                'string',
                'required',
            ],
        ];
    }
}
