<?php

namespace App\Http\Requests;

use App\Models\Produk;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProdukRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('produk_edit');
    }

    public function rules()
    {
        return [
            'seller_id'       => [
                'required',
                'integer',
            ],
            'kategori_produk' => [
                'required',
            ],
            'nama_produk'     => [
                'string',
                'min:1',
                'max:20',
                'required',
            ],
            'qty'             => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'harga'           => [
                'required',
            ],
        ];
    }
}
