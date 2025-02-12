<?php

namespace App\Http\Requests\V1\Materials;

use Illuminate\Foundation\Http\FormRequest;

class CostTypeRequest extends FormRequest
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
            "description" => "required|string",
            "effectiveTo" => "required|date_format:Y-m-d|after:today",
            "modifiedBy" => "required|string",
        ];
    }
}
