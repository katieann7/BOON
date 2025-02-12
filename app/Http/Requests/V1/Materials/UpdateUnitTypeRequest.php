<?php

namespace App\Http\Requests\V1\Materials;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateUnitTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $HTTPmethod = $request->method();
        
        if ($HTTPmethod  == 'PUT') {
            return [
                "description" => "required|string|unique:materials-unit_type_master,unit_type_description",
                "order" => "required|numeric|gt:0|unique:materials-unit_type_master,order",
                "effectiveTo" => "required|date_format:Y-m-d|after:today",
                "modifiedBy" => "required|string",
            ];
        } else {
            return [
                "description" => "sometimes|string|unique:materials-unit_type_master,unit_type_description",
                "order" => "sometimes|numeric|gt:0|unique:materials-unit_type_master,order",
                "effectiveTo" => "sometimes|date_format:Y-m-d|after:today",
                "modifiedBy" => "required|string",
            ];
        }
    }
}
