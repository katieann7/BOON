<?php

namespace App\Http\Requests\V1\Deliveries;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryConfirmationRequest extends FormRequest
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
            "storeCode" => 'required|string',
            "deliveryDate" => 'required|date_format:Y-m-d',
            "materialCode" => 'required|string',
            "quantity" => 'sometimes|numeric|regex:/^\d+(\.\d{1,2})?$/',
            "batchNumber" => 'sometimes|string',
            "unitCode" => 'required|varchar',
            "sellingQuantityConfirmed" => 'sometimes|numeric|regex:/^\d+(\.\d{1,2})?$/',
            "sellingUnitCode" => 'sometimes|string',
            "baseQuantityConfirmed" => 'sometimes|numeric|regex:/^\d+(\.\d{1,2})?$/',
            "baseUnitCode" => 'sometimes|string',
            "materialTypeCode" => 'required|string',
            "externalMaterialCost" => 'sometimes|numeric|regex:/^\d+(\.\d{1,2})?$/',
            "statusCode" => 'required|string',
            "confirmedOn" => 'sometimes|date_format:Y-m-d',
            "createdOn" => 'required|date_format:Y-m-d H:i:s',
            "createdBy" => 'required|string',
            "modifiedOn" => 'required|date_format:Y-m-d H:i:s',
            "modifiedBy" => 'required|string' 
        ];
    }
}
