<?php

namespace App\Http\Requests\V1\Materials;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
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
            'numberOfDays' => 'sometimes|gt:6',
            'deliveryDateRangeStart' => 'sometimes|date',
            'deliveryDateRangeEnd' => 'sometimes|date|after:deliveryDateRangeStart'
        ];
    }
}
