<?php

namespace App\Http\Requests\V1\Materials;

use Illuminate\Foundation\Http\FormRequest;

class PlantRequest extends FormRequest
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
            'plantCode' => 'required|string',
            'plantDescription' => 'required|string|max:50',
            'salesOrg' => 'required|max:4',
            'effectiveTo' => 'required|date|after:today',
            'modifiedBy' => 'required|string',
        ];
    }
}
