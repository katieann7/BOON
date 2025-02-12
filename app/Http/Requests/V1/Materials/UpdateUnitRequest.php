<?php

namespace App\Http\Requests\V1\Materials;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateUnitRequest extends FormRequest
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
                "code" => "required|string|unique:materials-unit_master,unit_code",
                "description" => "required|string|unique:materials-unit_master,unit_description",
                "effectiveTo" => "required|date_format:Y-m-d|after:today",
                "modifiedBy" => "required|string",
            ];
        } else {
            return [
                "code" => "sometimes|string|unique:materials-unit_master,unit_code",
                "description" => "sometimes|string|unique:materials-unit_master,unit_description",
                "effectiveTo" => "sometimes|date_format:Y-m-d|after:today",
                "modifiedBy" => "required|string",
            ];
        }
    }
}
