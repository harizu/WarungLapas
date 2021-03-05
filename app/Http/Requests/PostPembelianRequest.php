<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class PostPembelianRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('pembelian_access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'step' => [
                'bail',
                'required',
                'in:1,2',
            ],
        ];

        switch (request()->input('step')) {
            case 1:
                $rules += $this->step1Rules();
                break;

            case 2:
                $rules += $this->step2Rules();
                break;

            default:
                break;
        }

        return $rules;
    }

    private function step1Rules()
    {
        return [
            'nomor_registrasi' => [
                'required',
                'exists:warga_binaans,nomor_registrasi',
            ],
        ];
    }

    private function step2Rules()
    {
        return [
            'item' => [
                'required',
                'array',
            ],
            'item.*.id' => [
                'required',
                'exists:produks,id',
            ],
            'item.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],
            'biaya_layanan' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0',
            ],
        ];
    }
}
