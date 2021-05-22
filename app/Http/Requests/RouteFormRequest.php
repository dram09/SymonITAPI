<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouteFormRequest extends FormRequest
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
            'code'                  => 'required',
            'starDate'              => 'required',
            'date'                  => 'required',
            'deviceId'              => 'required',
            'driverId'              => 'required',
            'secondaryCode'         => 'required',
            'warehouseDepartureDate'=> 'required',
            'warehouseReturnDate'   => 'required',
            'carrier'               => 'required',
            'scheduledStartDate'    => 'required',
            'status'                => 'required'
        ];
    }
}
