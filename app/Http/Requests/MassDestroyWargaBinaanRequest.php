<?php

namespace App\Http\Requests;

use App\Models\WargaBinaan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyWargaBinaanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('warga_binaan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:warga_binaans,id',
        ];
    }
}
