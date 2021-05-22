<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PoiFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'                      => ['required','string'],
            'name'                      => ['required','string'],
            'longitude'                 => ['required','numeric'],
            'latitude'                  => ['required','numeric'],
            'enabled'                   => ['required','boolean'],
            'poiType'                   => ['required','string'],
            'phoneNumber'               => ['required','string'],
            'visitingFrequency'         => ['required','string'],
            'visitingDaysDevice1'       => ['required','string'],

            'longAddress'               => ['required','string'],
            'cep'                       => ['required','string'],

            // 'street'                    => ['required','string'],
            // 'number'                    => ['required','string'],
            // 'locality'                  => ['required','string'],
            // 'state'                     => ['required','string'],
            // 'country'                   => ['required','string'],

            // 'longAddress'             => ['required','string'],
            // 'cep'                     => ['required','string'],
            // 'doorNumber'              => ['required','string'],

            // 'deliveryAreaCode'        => ['required','string'],
            // 'salesAreaCode'           => ['required','string'],
            // 'originalAddress'         => ['required','string'],

        ];
    }
}
