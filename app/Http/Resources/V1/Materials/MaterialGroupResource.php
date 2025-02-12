<?php

namespace App\Http\Resources\V1\Materials;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "code" => $this->material_group_code,
            "description" => $this->material_group_description,
            "materialGroupType" =>$this->material_group_type_id,
            "costType" => $this->cost_type_id,
            "debit" => $this->gl_debit,
            "credit" => $this->gl_credit,
            "debitSpoilage" => $this->gl_debit_spoilage
        ];
    }
}
