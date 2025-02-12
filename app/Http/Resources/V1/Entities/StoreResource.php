<?php

namespace App\Http\Resources\V1\Entities;

use App\Http\Resources\V1\Materials\PlantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            "code" => $this->store_code,
            "description" => $this->store_description,
            "address" => $this->store_address,
            "longitude" => $this->longitude,
            "latitude" => $this->latitude,
            "costCenter" => new CostCenterResource($this->whenLoaded("CostCenter")),
            "plants" => PlantResource::collection($this->whenLoaded('plants')),
            "email" => $this->email_address,
        ];
    }
}
