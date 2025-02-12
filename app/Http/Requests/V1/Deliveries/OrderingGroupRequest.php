<?php

namespace App\Http\Requests\V1\Deliveries;

use Illuminate\Foundation\Http\FormRequest;

class OrderingGroupRequest extends FormRequest
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
            'storeId' => 'required|numeric|gt:0',
        ];
    }
}
