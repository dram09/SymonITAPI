<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WayPointFormRequest extends FormRequest
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
            'id'                            => 'required',
            'scheduledDate'                 => 'required',
            'poiId'                         => 'required',
            'totalValue'                    => 'required',
            'pallets'                       => 'required',
            'createAt'                      => 'required',
            'scheduledArrivalTimestamp'     => 'required',
            'scheduledDepartureTimestamp'   => 'required',
            'visitOrder'                    => 'required',
            'activities'                    => 'required'
        ];
    }
}
