<?php

namespace App\Http\Requests\V1\Materials;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateMaterialGroupTypeRequest extends FormRequest
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
    public function rules(Request $request)
    {

        $HTTPmethod = $request->method();
        
        if ($HTTPmethod  == 'PUT') {
            return [
                "description" => "required|string|unique:materials-material_group_type_master,material_group_type_description",
                "effectiveTo" => "required|date_format:Y-m-d|after:today",
                "modifiedBy" => "required|string",
            ];
        } else {
            return [
                "description" => "sometimes|string|unique:materials-material_group_type_master,material_group_type_description",
                "effectiveTo" => "sometimes|date_format:Y-m-d|after:today",
                "modifiedBy" => "required|string",
            ];
        }
    }
}
