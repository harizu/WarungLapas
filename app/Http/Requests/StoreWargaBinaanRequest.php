<?php

namespace App\Http\Requests;

use App\Models\WargaBinaan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreWargaBinaanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('warga_binaan_create');
    }

    public function rules()
    {
        return [
            'nomor_registrasi'  => [
                'string',
                'required',
                'unique:warga_binaans',
            ],
            'nama_warga_binaan' => [
                'string',
                'required',
            ],
            'kasus'             => [
                'string',
                'nullable',
            ],
        ];
    }
}
