<?php

namespace App\Http\Resources\V1\Entities;

use Illuminate\Http\Resources\Json\JsonResource;

class CostCenterResource extends JsonResource
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
            "id" => $this->id,
            "code" => $this->cost_center_code,
            "description" => $this->cost_center_description,
            "groupId" => $this->cost_center_group_id,
            "companyId" => $this->company_id,
        ];
    }
}
