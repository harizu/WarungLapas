<?php

namespace App\Http\Requests;

use App\Models\WargaBinaan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWargaBinaanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('warga_binaan_edit');
    }

    public function rules()
    {
        return [
            'nomor_registrasi'  => [
                'string',
                'required',
                'unique:warga_binaans,nomor_registrasi,' . request()->route('warga_binaan')->id,
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
