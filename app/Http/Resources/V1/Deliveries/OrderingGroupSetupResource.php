<?php

namespace App\Http\Resources\V1\Deliveries;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderingGroupSetupResource extends JsonResource
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
            "code" => $this->ordering_group_code,
            "description" => $this->ordering_group_description,
            "effectiveFrom" => $this->effective_from,
            "effectiveTo" => $this->effective_to,
            "modifiedOn" => $this->modified_on,
            "modifiedBy" => $this->modified_by,
        ];
    }
}
