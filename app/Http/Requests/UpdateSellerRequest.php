<?php

namespace App\Http\Requests;

use App\Models\Seller;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSellerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('seller_edit');
    }

    public function rules()
    {
        return [
            'nama_seller'   => [
                'string',
                'required',
            ],
            'alamat_seller' => [
                'string',
                'nullable',
            ],
            'nomor_telp'    => [
                'string',
                'nullable',
            ],
        ];
    }
}
