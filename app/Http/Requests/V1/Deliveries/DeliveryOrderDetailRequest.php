<?php

namespace App\Http\Requests\V1\Deliveries;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryOrderDetailRequest extends FormRequest
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
            "storeCode" => 'required|alpha_num',
            "deliveryDate" => 'required|date_format:Y-m-d|after:today', 
            "orderingGroupCode" => 'required|alpha_num',
            "plantCode" => 'required|alpha_num',
            "materialCode" => 'required|alpha_num',
            "unitCode" => 'required|string',
            "quantityOrdered" => 'required|numeric',
            "createdBy" => 'required|string',
            "modifiedBy" => 'sometimes|string',
        ];
    }
}
