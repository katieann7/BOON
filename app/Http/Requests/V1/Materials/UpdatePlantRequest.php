<?php

namespace App\Http\Requests\V1\Materials;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlantRequest extends FormRequest
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
        $HTTPmethod = $this->method();

        if ($HTTPmethod == 'PUT') {
            return [
                'plantCode' => 'required|string|unique:materials-plant_master,plant_code|min:4|max:4',
                'plantDescription' => 'required|string|max:50|unique:materials-plant_master,plant_description',
                'salesOrg' => 'required|max:4',
                'effectiveTo' => 'required|date|after:today',
                'modifiedBy' => 'required|string',
            ];
        } else {
            return [
                'plantCode' => 'sometimes|string|unique:materials-plant_master,plant_code|min:4|max:4',
                'plantDescription' => 'sometimes|string|max:50|unique:materials-plant_master,plant_description',
                'salesOrg' => 'sometimes|max:4',
                'effectiveTo' => 'sometimes|date|after:today',
                'modifiedBy' => 'required|string',
            ];
        }
    }
}
