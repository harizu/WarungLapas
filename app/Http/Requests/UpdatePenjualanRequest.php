<?php

namespace App\Http\Requests;

use App\Models\Penjualan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePenjualanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('penjualan_edit');
    }

    public function rules()
    {
        return [
            'trx_no'      => [
                'string',
                'required',
                'unique:penjualans,trx_no,' . request()->route('penjualan')->id,
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
