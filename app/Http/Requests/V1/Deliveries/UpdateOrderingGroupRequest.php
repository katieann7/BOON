<?php

namespace App\Http\Requests\V1\Deliveries;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderingGroupRequest extends FormRequest
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
                "orderingGroupDescription" => "required|string|unique:deliveries-ordering_group_master,ordering_group_description",
                "effectiveTo" => "required|date_format:Y-m-d|after:today",
                "modifiedBy" => "required|string",
            ];
        } else {
            return [
                "orderingGroupDescription" => "sometimes|string|unique:deliveries-ordering_group_master,ordering_group_description",
                "effectiveTo" => "sometimes|date_format:Y-m-d|after:today",
                "modifiedBy" => "required|string",
            ];
        }
    }
}
